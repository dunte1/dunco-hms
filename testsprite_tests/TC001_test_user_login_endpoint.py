import requests

BASE_URL = "http://localhost:8001"
LOGIN_ENDPOINT = f"{BASE_URL}/login"
TIMEOUT = 30

def test_user_login_endpoint():
    headers = {
        "Content-Type": "application/json"
    }

    valid_payload = {
        "email": "admin@example.com",
        "password": "password"
    }
    invalid_payload = {
        "email": "admin@example.com",
        "password": "wrongpassword"
    }

    # Test valid login credentials
    try:
        response_valid = requests.post(LOGIN_ENDPOINT, json=valid_payload, headers=headers, timeout=TIMEOUT)
        assert response_valid.status_code == 200, f"Expected 200, got {response_valid.status_code}"
    except requests.RequestException as e:
        assert False, f"Request failed for valid credentials: {e}"

    # Test invalid login credentials
    try:
        response_invalid = requests.post(LOGIN_ENDPOINT, json=invalid_payload, headers=headers, timeout=TIMEOUT)
        assert response_invalid.status_code == 401, f"Expected 401, got {response_invalid.status_code}"
    except requests.RequestException as e:
        assert False, f"Request failed for invalid credentials: {e}"

test_user_login_endpoint()