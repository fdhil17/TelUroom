import os
import time
import pytest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.ui import Select
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
from selenium.common.exceptions import TimeoutException

BASE_URL = "http://127.0.0.1:8000"

LOGISTIK_EMAIL = "logistik@teluroom.com"
LOGISTIK_PASS = "admin"
SSC_EMAIL = "ssc@teluroom.com"
SSC_PASS = "admin"
MAHASISWA_EMAIL = "auliarahmanramadhan@student.telkomuniversity.ac.id"
MAHASISWA_PASS = "aulia123"

@pytest.fixture(scope="module")
def setup_database():
    print("\n[+] Mereset database untuk modul CRUD Kelola Ruangan (Menggunakan database testing sementara)...")
    os.system("php artisan migrate:fresh --seed > NUL 2>&1")
    os.system('php artisan tinker --execute="User::firstOrCreate([\'email\' => \'auliarahmanramadhan@student.telkomuniversity.ac.id\'], [\'name\' => \'Aulia Rahman R\', \'nim\' => \'102062400017\', \'prodi\' => \'S1 Sistem Informasi\', \'password\' => Hash::make(\'aulia123\'), \'role\' => \'mahasiswa\']); User::firstOrCreate([\'email\' => \'logistik@teluroom.com\'], [\'name\' => \'Logistik TelUroom\', \'password\' => Hash::make(\'admin\'), \'role\' => \'logistik\']); User::firstOrCreate([\'email\' => \'ssc@teluroom.com\'], [\'name\' => \'SSC TelUroom\', \'password\' => Hash::make(\'admin\'), \'role\' => \'ssc\']);" > NUL 2>&1')
    yield

@pytest.fixture(scope="function")
def driver():
    options = webdriver.ChromeOptions()
    options.add_argument("--start-maximized")
    options.add_argument("--window-size=1920,1080")
    options.add_argument("--disable-autofill")
    options.add_experimental_option("prefs", {
        "credentials_enable_service": False,
        "profile.password_manager_enabled": False
    })
    service = Service(ChromeDriverManager().install())
    d = webdriver.Chrome(service=service, options=options)
    yield d
    d.quit()

def logout(driver):
    user_dropdown = driver.find_element(By.XPATH, "//*[contains(@class, 'teluroom-user-btn') or contains(text(), 'Aulia') or contains(text(), 'Logistik') or contains(text(), 'SSC')]")
    driver.execute_script("arguments[0].click();", user_dropdown)
    time.sleep(1)
    logout_btn = driver.find_element(By.XPATH, "//form[contains(@action, 'logout')]/button")
    driver.execute_script("arguments[0].click();", logout_btn)
    WebDriverWait(driver, 10).until(lambda d: d.current_url.rstrip('/').endswith("/") or d.current_url.endswith("login"))
    time.sleep(2)

def login(driver, email, password):
    driver.get(f"{BASE_URL}/login")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.ID, "email")))
    email_input = driver.find_element(By.ID, "email")
    driver.execute_script("arguments[0].value = arguments[1]; arguments[0].dispatchEvent(new Event('input', { bubbles: true })); arguments[0].dispatchEvent(new Event('change', { bubbles: true }));", email_input, email)
    
    password_input = driver.find_element(By.ID, "password")
    driver.execute_script("arguments[0].value = arguments[1]; arguments[0].dispatchEvent(new Event('input', { bubbles: true })); arguments[0].dispatchEvent(new Event('change', { bubbles: true }));", password_input, password)
    
    submit_btn = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
    driver.execute_script("arguments[0].click();", submit_btn)
    time.sleep(1)
    
    try:
        WebDriverWait(driver, 10).until(EC.url_contains("/dashboard"))
    except Exception as e:
        driver.save_screenshot("test_CRUD_Kelola_Ruangan/debug_login_error.png")
        raise e
    time.sleep(1)

@pytest.mark.order(1)
def test_1_tambah_ruangan(driver, setup_database):
    print("\n===> Menjalankan Skenario 1: Tambah Ruangan Baru (5.01) <===")
    login(driver, LOGISTIK_EMAIL, LOGISTIK_PASS)
    
    # Pilih tombol kelola ruangan di panel kiri
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Kelola Ruangan")))
    menu_kelola_ruangan = driver.find_element(By.PARTIAL_LINK_TEXT, "Kelola Ruangan")
    driver.execute_script("arguments[0].click();", menu_kelola_ruangan)
    
    WebDriverWait(driver, 10).until(EC.url_contains("logistik/ruangan"))
    
    # Klik tombol + Tambah Ruangan
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Tambah Ruangan")))
    tambah_btn = driver.find_element(By.PARTIAL_LINK_TEXT, "Tambah Ruangan")
    driver.execute_script("arguments[0].click();", tambah_btn)
    
    time.sleep(2)
    
    # Isi form
    driver.execute_script("arguments[0].value = '5.01'; arguments[0].dispatchEvent(new Event('input', { bubbles: true }));", driver.find_element(By.NAME, "kode_ruangan"))
    driver.execute_script("arguments[0].value = '5.01'; arguments[0].dispatchEvent(new Event('input', { bubbles: true }));", driver.find_element(By.NAME, "nama_ruangan"))
    driver.execute_script("arguments[0].value = '1'; arguments[0].dispatchEvent(new Event('input', { bubbles: true }));", driver.find_element(By.NAME, "lantai"))
    driver.execute_script("arguments[0].value = '40'; arguments[0].dispatchEvent(new Event('input', { bubbles: true }));", driver.find_element(By.NAME, "kapasitas"))
    
    status_select = Select(driver.find_element(By.ID, "status"))
    status_select.select_by_value("tersedia")
    
    driver.execute_script("arguments[0].click();", driver.find_element(By.XPATH, "//button[contains(text(), 'Simpan Data')]"))
    
    WebDriverWait(driver, 10).until(EC.url_contains("logistik/ruangan"))
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.XPATH, "//*[contains(text(), 'berhasil')]")))
    
    # Verifikasi nama ruangan ada di tabel
    assert len(driver.find_elements(By.XPATH, "//*[contains(text(), '5.01')]")) > 0
    driver.save_screenshot("test_CRUD_Kelola_Ruangan/skenario1_tambah_ruangan_sukses.png")

@pytest.mark.order(2)
def test_2_edit_ruangan(driver, setup_database):
    print("\n===> Menjalankan Skenario 2: Edit Status Ruangan (5.01 -> Maintenance) <===")
    login(driver, LOGISTIK_EMAIL, LOGISTIK_PASS)
    
    # Pilih tombol kelola ruangan di panel kiri
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Kelola Ruangan")))
    menu_kelola_ruangan = driver.find_element(By.PARTIAL_LINK_TEXT, "Kelola Ruangan")
    driver.execute_script("arguments[0].click();", menu_kelola_ruangan)
    
    WebDriverWait(driver, 10).until(EC.url_contains("logistik/ruangan"))
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.XPATH, "//*[contains(text(), '5.01')]")))
    
    # Klik tombol yang bergambar pensil di tabel (untuk ruangan 5.01)
    edit_btn = driver.find_element(By.XPATH, "//td[contains(text(), '5.01')]/following-sibling::td//a[@title='Edit']")
    driver.get(edit_btn.get_attribute("href"))
    
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "nama_ruangan")))
    
    # Ubah status ruangan menjadi maintenance
    status_select = Select(driver.find_element(By.ID, "status"))
    status_select.select_by_value("maintenance")
    
    # Tekan simpan data
    driver.execute_script("arguments[0].click();", driver.find_element(By.XPATH, "//button[contains(text(), 'Simpan Data')]"))
    
    WebDriverWait(driver, 10).until(EC.url_contains("logistik/ruangan"))
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.XPATH, "//*[contains(text(), 'berhasil')]")))
    
    driver.save_screenshot("test_CRUD_Kelola_Ruangan/skenario2_edit_ruangan_sukses.png")

@pytest.mark.order(3)
def test_3_hapus_ruangan_ada_reservasi(driver, setup_database):
    print("\n===> Menjalankan Skenario 3: Hapus Ruangan 1.02 yang sedang direservasi <===")
    
    # 1. Login Mahasiswa dan Reservasi 1.02
    login(driver, MAHASISWA_EMAIL, MAHASISWA_PASS)
    driver.get(f"{BASE_URL}/mahasiswa/reservasi/create")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.ID, "ruangan_id")))
    
    # Pilih ruangan 1.02 menggunakan Select
    ruangan_select = Select(driver.find_element(By.ID, "ruangan_id"))
    ruangan_option = driver.find_element(By.XPATH, "//select[@id='ruangan_id']/option[contains(text(), '1.02')]")
    ruangan_select.select_by_value(ruangan_option.get_attribute("value"))
    
    besok = "2026-07-21"
    driver.execute_script(f"arguments[0].value = '{besok}';", driver.find_element(By.ID, "tanggal_reservasi"))
    driver.execute_script("arguments[0].value = '08:00';", driver.find_element(By.ID, "jam_mulai"))
    driver.execute_script("arguments[0].value = '10:00';", driver.find_element(By.ID, "jam_selesai"))
    driver.find_element(By.ID, "keperluan").send_keys("Pinjam untuk pengujian hapus ruangan")
    
    submit_res_btn = driver.find_element(By.XPATH, "//button[contains(text(), 'Kirim Pengajuan')]")
    driver.execute_script("arguments[0].click();", submit_res_btn)
    
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.XPATH, "//*[contains(text(), 'berhasil')]")))
    logout(driver)
    
    # 2. Login SSC dan Setujui
    login(driver, SSC_EMAIL, SSC_PASS)
    driver.get(f"{BASE_URL}/ssc/approval")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Tinjau")))
    tinjau_link = driver.find_element(By.PARTIAL_LINK_TEXT, "Tinjau")
    driver.get(tinjau_link.get_attribute("href"))
    
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.XPATH, "//button[contains(text(), 'Verifikasi & Teruskan')]")))
    catatan_box = driver.find_element(By.ID, "catatan_ssc")
    driver.execute_script("arguments[0].value = 'ACC SSC'; arguments[0].dispatchEvent(new Event('input', { bubbles: true }));", catatan_box)
    
    btn_verify = driver.find_element(By.XPATH, "//button[contains(text(), 'Verifikasi & Teruskan')]")
    driver.execute_script("arguments[0].click();", btn_verify)
    
    WebDriverWait(driver, 10).until(EC.element_to_be_clickable((By.ID, "confirmModalBtn")))
    time.sleep(1)
    driver.execute_script("arguments[0].click();", driver.find_element(By.ID, "confirmModalBtn"))
    WebDriverWait(driver, 10).until(lambda d: d.current_url.rstrip('/').endswith("ssc/approval"))
    logout(driver)
    
    # 3. Login Logistik dan Setujui, lalu Hapus Ruangan
    login(driver, LOGISTIK_EMAIL, LOGISTIK_PASS)
    
    # Setujui reservasi
    driver.get(f"{BASE_URL}/logistik/approval")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Tinjau")))
    tinjau_link_log = driver.find_element(By.PARTIAL_LINK_TEXT, "Tinjau")
    driver.get(tinjau_link_log.get_attribute("href"))
    
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.XPATH, "//button[@data-action='approve']")))
    catatan_log = driver.find_element(By.ID, "catatan_logistik")
    driver.execute_script("arguments[0].value = 'ACC Logistik'; arguments[0].dispatchEvent(new Event('input', { bubbles: true }));", catatan_log)
    
    btn_approve = driver.find_element(By.XPATH, "//button[@data-action='approve']")
    driver.execute_script("arguments[0].click();", btn_approve)
    
    WebDriverWait(driver, 10).until(EC.element_to_be_clickable((By.ID, "confirmModalBtn")))
    time.sleep(1)
    driver.execute_script("arguments[0].click();", driver.find_element(By.ID, "confirmModalBtn"))
    WebDriverWait(driver, 10).until(lambda d: d.current_url.rstrip('/').endswith("logistik/approval"))
    
    # Pilih tombol kelola ruangan di panel kiri
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Kelola Ruangan")))
    menu_kelola_ruangan = driver.find_element(By.PARTIAL_LINK_TEXT, "Kelola Ruangan")
    driver.execute_script("arguments[0].click();", menu_kelola_ruangan)
    
    WebDriverWait(driver, 10).until(EC.url_contains("logistik/ruangan"))
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.XPATH, "//*[contains(text(), '1.02')]")))
    
    # Klik tombol berlogo tong sampah warna merah di baris 1.02
    delete_btn = driver.find_element(By.XPATH, "//td[contains(text(), '1.02')]/following-sibling::td//button[@title='Hapus']")
    driver.execute_script("arguments[0].click();", delete_btn)
    
    WebDriverWait(driver, 10).until(EC.visibility_of_element_located((By.ID, "confirmDeleteModal")))
    time.sleep(1)
    
    driver.execute_script("arguments[0].click();", driver.find_element(By.ID, "confirmDeleteBtn"))
    
    # Tunggu pesan error muncul (tidak boleh dihapus karena reservasi aktif)
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.XPATH, "//*[contains(text(), 'masih ada reservasi aktif')]")))
    
    driver.save_screenshot("test_CRUD_Kelola_Ruangan/skenario3_hapus_ruangan_gagal.png")
