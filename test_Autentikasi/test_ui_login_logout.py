import pytest
import time
import os
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager

BASE_URL = "http://localhost:8000"
VALID_EMAIL = "auliarahmanramadhan@student.telkomuniversity.ac.id"
VALID_PASS = "aulia123"
INVALID_EMAIL = "tidak_terdaftar@teluroom.com"
INVALID_PASS = "password_salah_123"

@pytest.fixture(scope="module", autouse=True)
def setup_database():
    print("\n[+] Mereset database untuk testing Autentikasi...")
    os.system("php artisan migrate:fresh --seed > NUL 2>&1")
    os.system('php artisan tinker --execute="User::firstOrCreate([\'email\' => \'auliarahmanramadhan@student.telkomuniversity.ac.id\'], [\'name\' => \'Aulia Rahman R\', \'nim\' => \'102062400017\', \'prodi\' => \'S1 Sistem Informasi\', \'password\' => Hash::make(\'aulia123\'), \'role\' => \'mahasiswa\']);" > NUL 2>&1')
    yield

@pytest.fixture(scope="function")
def driver():
    options = webdriver.ChromeOptions()
    options.add_argument("--start-maximized")
    options.add_argument("--window-size=1920,1080")
    service = Service(ChromeDriverManager().install())
    d = webdriver.Chrome(service=service, options=options)
    yield d
    d.quit()

@pytest.mark.order(1)
def test_1_login_gagal_email_tidak_terdaftar(driver):
    driver.get(f"{BASE_URL}/login")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.ID, "email")))
    
    driver.find_element(By.ID, "email").send_keys(INVALID_EMAIL)
    driver.find_element(By.ID, "password").send_keys(VALID_PASS)
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
    
    # Verifikasi gagal login dan pesan error muncul
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.XPATH, "//*[contains(text(), 'Kredensial') or contains(text(), 'These credentials')]")))
    assert "/login" in driver.current_url
    driver.save_screenshot("test_Autentikasi/state_login_gagal_email.png")

@pytest.mark.order(2)
def test_2_login_gagal_password_salah(driver):
    driver.get(f"{BASE_URL}/login")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.ID, "email")))
    
    driver.find_element(By.ID, "email").send_keys(VALID_EMAIL)
    driver.find_element(By.ID, "password").send_keys(INVALID_PASS)
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
    
    # Verifikasi gagal login
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.XPATH, "//*[contains(text(), 'Kredensial') or contains(text(), 'These credentials')]")))
    assert "/login" in driver.current_url
    driver.save_screenshot("test_Autentikasi/state_login_gagal_password.png")

@pytest.mark.order(3)
def test_3_login_berhasil_valid(driver):
    driver.get(f"{BASE_URL}/login")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.ID, "email")))
    
    driver.find_element(By.ID, "email").send_keys(VALID_EMAIL)
    driver.find_element(By.ID, "password").send_keys(VALID_PASS)
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
    
    # Verifikasi redirect ke dashboard
    WebDriverWait(driver, 10).until(EC.url_contains("/dashboard"))
    assert "/dashboard" in driver.current_url
    driver.save_screenshot("test_Autentikasi/state_login_berhasil.png")

@pytest.mark.order(4)
def test_4_logout_berhasil(driver):
    # Setup: Login terlebih dahulu
    driver.get(f"{BASE_URL}/login")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.ID, "email")))
    driver.find_element(By.ID, "email").send_keys(VALID_EMAIL)
    driver.find_element(By.ID, "password").send_keys(VALID_PASS)
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
    WebDriverWait(driver, 10).until(EC.url_contains("/dashboard"))
    
    # Aksi Logout
    # Klik dropdown profile/nama user, di Laravel breeze/jetstream biasanya ada di pojok kanan atas
    user_dropdown = driver.find_element(By.XPATH, f"//*[contains(text(), 'Aulia Rahman R')]")
    driver.execute_script("arguments[0].click();", user_dropdown)
    time.sleep(1)
    
    # Klik tombol Log Out
    logout_btn = driver.find_element(By.XPATH, "//form[contains(@action, 'logout')]/button")
    driver.execute_script("arguments[0].click();", logout_btn)
    
    # Verifikasi kembali ke halaman awal atau login
    WebDriverWait(driver, 10).until(lambda d: d.current_url.rstrip('/').endswith("/") or d.current_url.endswith("login"))
    
    # Verifikasi sesi sudah mati (mencoba paksa buka dashboard)
    driver.get(f"{BASE_URL}/dashboard")
    WebDriverWait(driver, 10).until(EC.url_contains("/login"))
    driver.save_screenshot("test_Autentikasi/state_logout_berhasil.png")
