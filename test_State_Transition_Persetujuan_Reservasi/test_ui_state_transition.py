import pytest
import time
import os
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.ui import Select
from webdriver_manager.chrome import ChromeDriverManager

BASE_URL = "http://localhost:8000"
MAHASISWA_EMAIL = "auliarahmanramadhan@student.telkomuniversity.ac.id"
MAHASISWA_PASS = "aulia123"
SSC_EMAIL = "ssc@teluroom.com"
SSC_PASS = "admin"
LOGISTIK_EMAIL = "logistik@teluroom.com"
LOGISTIK_PASS = "admin"

@pytest.fixture(scope="function")
def setup_database():
    print("\n[+] Mereset database untuk skenario baru...")
    os.system("php artisan migrate:fresh --seed > NUL 2>&1")
    os.system('php artisan tinker --execute="User::firstOrCreate([\'email\' => \'auliarahmanramadhan@student.telkomuniversity.ac.id\'], [\'name\' => \'Aulia Rahman R\', \'nim\' => \'102062400017\', \'prodi\' => \'S1 Sistem Informasi\', \'password\' => Hash::make(\'aulia123\'), \'role\' => \'mahasiswa\']); User::firstOrCreate([\'email\' => \'ssc@teluroom.com\'], [\'name\' => \'SSC TelUroom\', \'password\' => Hash::make(\'admin\'), \'role\' => \'ssc\']); User::firstOrCreate([\'email\' => \'logistik@teluroom.com\'], [\'name\' => \'Logistik TelUroom\', \'password\' => Hash::make(\'admin\'), \'role\' => \'logistik\']);" > NUL 2>&1')
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
    user_dropdown = driver.find_element(By.XPATH, "//*[contains(@class, 'teluroom-user-btn') or contains(text(), 'Aulia') or contains(text(), 'SSC') or contains(text(), 'Logistik')]")
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
    driver.save_screenshot("test_State_Transition_Persetujuan_Reservasi/debug_login_before_submit.png")
    submit_btn = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
    driver.execute_script("arguments[0].click();", submit_btn)
    time.sleep(1)
    driver.save_screenshot("test_State_Transition_Persetujuan_Reservasi/debug_login_after_submit.png")
    try:
        WebDriverWait(driver, 10).until(EC.url_contains("/dashboard"))
    except Exception as e:
        driver.save_screenshot("test_State_Transition_Persetujuan_Reservasi/debug_login_error.png")
        raise e

@pytest.mark.order(1)
def test_1_skenario_accSSC_accLOGISTIK(driver, setup_database):
    print("\n===> Menjalankan Skenario 1: Disetujui Sepenuhnya <===")
    # 1. MAHASISWA MEMBUAT RESERVASI
    login(driver, MAHASISWA_EMAIL, MAHASISWA_PASS)
    driver.get(f"{BASE_URL}/mahasiswa/reservasi/create")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.ID, "ruangan_id")))
    ruangan_select = Select(driver.find_element(By.ID, "ruangan_id"))
    ruangan_select.select_by_index(1)
    
    besok = "2026-07-21"
    driver.execute_script(f"arguments[0].value = '{besok}';", driver.find_element(By.ID, "tanggal_reservasi"))
    driver.execute_script("arguments[0].value = '08:00';", driver.find_element(By.ID, "jam_mulai"))
    driver.execute_script("arguments[0].value = '10:00';", driver.find_element(By.ID, "jam_selesai"))
    driver.find_element(By.ID, "keperluan").send_keys("UI Testing Skenario 1 - ACC Semua")
    driver.find_element(By.XPATH, "//button[contains(text(), 'Kirim Pengajuan')]").click()
    time.sleep(1)
    driver.save_screenshot("test_State_Transition_Persetujuan_Reservasi/debug_error.png")
    WebDriverWait(driver, 10).until(EC.url_contains("mahasiswa/reservasi"))
    time.sleep(1)
    driver.save_screenshot("test_State_Transition_Persetujuan_Reservasi/skenario1_state_1_menunggu_ssc.png")
    
    logout(driver)

    # 2. SSC MENYETUJUI
    login(driver, SSC_EMAIL, SSC_PASS)
    driver.get(f"{BASE_URL}/ssc/approval")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Tinjau")))
    tinjau_link = driver.find_element(By.PARTIAL_LINK_TEXT, "Tinjau")
    driver.get(tinjau_link.get_attribute("href"))
    
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.XPATH, "//button[contains(text(), 'Verifikasi & Teruskan')]")))
    catatan_box = driver.find_element(By.ID, "catatan_ssc")
    driver.execute_script("arguments[0].value = 'Disetujui oleh bot Selenium SSC';", catatan_box)
    time.sleep(1)
    btn_verify = driver.find_element(By.XPATH, "//button[contains(text(), 'Verifikasi & Teruskan')]")
    driver.execute_script("arguments[0].click();", btn_verify)
    
    WebDriverWait(driver, 10).until(EC.element_to_be_clickable((By.ID, "confirmModalBtn")))
    time.sleep(1)
    driver.execute_script("arguments[0].click();", driver.find_element(By.ID, "confirmModalBtn"))
    
    WebDriverWait(driver, 10).until(lambda d: d.current_url.rstrip('/').endswith("ssc/approval"))
    time.sleep(1)
    driver.save_screenshot("test_State_Transition_Persetujuan_Reservasi/skenario1_state_2_menunggu_logistik.png")
    
    logout(driver)

    # 3. LOGISTIK MENYETUJUI
    login(driver, LOGISTIK_EMAIL, LOGISTIK_PASS)
    driver.get(f"{BASE_URL}/logistik/approval")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Tinjau")))
    driver.get(driver.find_element(By.PARTIAL_LINK_TEXT, "Tinjau").get_attribute("href"))
    
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.XPATH, "//button[@data-action='approve']")))
    time.sleep(1)
    btn_approve = driver.find_element(By.XPATH, "//button[@data-action='approve']")
    driver.execute_script("arguments[0].click();", btn_approve)
    
    WebDriverWait(driver, 10).until(EC.visibility_of_element_located((By.ID, "confirmModalBtn")))
    time.sleep(1)
    driver.execute_script("arguments[0].click();", driver.find_element(By.ID, "confirmModalBtn"))
    
    WebDriverWait(driver, 10).until(lambda d: d.current_url.rstrip('/').endswith("logistik/approval"))
    time.sleep(1)
    driver.save_screenshot("test_State_Transition_Persetujuan_Reservasi/skenario1_state_3_disetujui.png")

@pytest.mark.order(2)
def test_2_skenario_accSSC_ditolakLOGISTIK(driver, setup_database):
    print("\n===> Menjalankan Skenario 2: ACC SSC & Ditolak LOGISTIK <===")
    # 1. MAHASISWA MEMBUAT RESERVASI
    login(driver, MAHASISWA_EMAIL, MAHASISWA_PASS)
    driver.get(f"{BASE_URL}/mahasiswa/reservasi/create")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.ID, "ruangan_id")))
    ruangan_select = Select(driver.find_element(By.ID, "ruangan_id"))
    ruangan_select.select_by_index(1)
    
    besok = "2026-07-21"
    driver.execute_script(f"arguments[0].value = '{besok}';", driver.find_element(By.ID, "tanggal_reservasi"))
    driver.execute_script("arguments[0].value = '08:00';", driver.find_element(By.ID, "jam_mulai"))
    driver.execute_script("arguments[0].value = '10:00';", driver.find_element(By.ID, "jam_selesai"))
    driver.find_element(By.ID, "keperluan").send_keys("UI Testing Skenario 2 - Ditolak Logistik")
    driver.find_element(By.XPATH, "//button[contains(text(), 'Kirim Pengajuan')]").click()
    
    WebDriverWait(driver, 10).until(EC.url_contains("mahasiswa/reservasi"))
    logout(driver)

    # 2. SSC MENYETUJUI
    login(driver, SSC_EMAIL, SSC_PASS)
    driver.get(f"{BASE_URL}/ssc/approval")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Tinjau")))
    tinjau_link = driver.find_element(By.PARTIAL_LINK_TEXT, "Tinjau")
    driver.get(tinjau_link.get_attribute("href"))
    
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.XPATH, "//button[contains(text(), 'Verifikasi & Teruskan')]")))
    catatan_box = driver.find_element(By.ID, "catatan_ssc")
    driver.execute_script("arguments[0].value = 'Disetujui oleh bot Selenium SSC';", catatan_box)
    time.sleep(1)
    btn_verify = driver.find_element(By.XPATH, "//button[contains(text(), 'Verifikasi & Teruskan')]")
    driver.execute_script("arguments[0].click();", btn_verify)
    
    WebDriverWait(driver, 10).until(EC.element_to_be_clickable((By.ID, "confirmModalBtn")))
    time.sleep(1)
    driver.execute_script("arguments[0].click();", driver.find_element(By.ID, "confirmModalBtn"))
    
    WebDriverWait(driver, 10).until(lambda d: d.current_url.rstrip('/').endswith("ssc/approval"))
    logout(driver)

    # 3. LOGISTIK MENOLAK
    login(driver, LOGISTIK_EMAIL, LOGISTIK_PASS)
    driver.get(f"{BASE_URL}/logistik/approval")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Tinjau")))
    driver.get(driver.find_element(By.PARTIAL_LINK_TEXT, "Tinjau").get_attribute("href"))
    
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.ID, "catatan_logistik")))
    time.sleep(1)
    catatan_box = driver.find_element(By.ID, "catatan_logistik")
    driver.execute_script("arguments[0].value = 'Ditolak karena ruangan sedang dipakai untuk kegiatan mendadak'; arguments[0].dispatchEvent(new Event('input', { bubbles: true })); arguments[0].dispatchEvent(new Event('change', { bubbles: true }));", catatan_box)
    time.sleep(1)
    
    btn_reject = driver.find_element(By.XPATH, "//button[@data-action='reject']")
    driver.execute_script("arguments[0].click();", btn_reject)
    
    WebDriverWait(driver, 10).until(EC.visibility_of_element_located((By.ID, "confirmModalBtn")))
    time.sleep(1)
    driver.execute_script("arguments[0].click();", driver.find_element(By.ID, "confirmModalBtn"))
    
    WebDriverWait(driver, 10).until(lambda d: d.current_url.rstrip('/').endswith("logistik/approval"))
    time.sleep(1)
    driver.save_screenshot("test_State_Transition_Persetujuan_Reservasi/skenario2_state_3_ditolak_logistik.png")

@pytest.mark.order(3)
def test_3_skenario_ditolakSSC(driver, setup_database):
    print("\n===> Menjalankan Skenario 3: Ditolak SSC <===")
    # 1. MAHASISWA MEMBUAT RESERVASI
    login(driver, MAHASISWA_EMAIL, MAHASISWA_PASS)
    driver.get(f"{BASE_URL}/mahasiswa/reservasi/create")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.ID, "ruangan_id")))
    ruangan_select = Select(driver.find_element(By.ID, "ruangan_id"))
    ruangan_select.select_by_index(1)
    
    besok = "2026-07-21"
    driver.execute_script(f"arguments[0].value = '{besok}';", driver.find_element(By.ID, "tanggal_reservasi"))
    driver.execute_script("arguments[0].value = '08:00';", driver.find_element(By.ID, "jam_mulai"))
    driver.execute_script("arguments[0].value = '10:00';", driver.find_element(By.ID, "jam_selesai"))
    driver.find_element(By.ID, "keperluan").send_keys("UI Testing Skenario 3 - Ditolak SSC")
    driver.find_element(By.XPATH, "//button[contains(text(), 'Kirim Pengajuan')]").click()
    
    WebDriverWait(driver, 10).until(EC.url_contains("mahasiswa/reservasi"))
    logout(driver)

    # 2. SSC MENOLAK
    login(driver, SSC_EMAIL, SSC_PASS)
    driver.get(f"{BASE_URL}/ssc/approval")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.PARTIAL_LINK_TEXT, "Tinjau")))
    driver.get(driver.find_element(By.PARTIAL_LINK_TEXT, "Tinjau").get_attribute("href"))
    
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.ID, "catatan_ssc")))
    catatan_box = driver.find_element(By.ID, "catatan_ssc")
    driver.execute_script("arguments[0].value = 'Ditolak karena tidak memenuhi kelengkapan administrasi';", catatan_box)
    time.sleep(1)
    
    btn_reject = driver.find_element(By.XPATH, "//button[@data-action='reject']")
    driver.execute_script("arguments[0].click();", btn_reject)
    
    WebDriverWait(driver, 10).until(EC.element_to_be_clickable((By.ID, "confirmModalBtn")))
    time.sleep(1)
    driver.execute_script("arguments[0].click();", driver.find_element(By.ID, "confirmModalBtn"))
    
    WebDriverWait(driver, 10).until(lambda d: d.current_url.rstrip('/').endswith("ssc/approval"))
    time.sleep(1)
    driver.save_screenshot("test_State_Transition_Persetujuan_Reservasi/skenario3_state_2_ditolak_ssc.png")
