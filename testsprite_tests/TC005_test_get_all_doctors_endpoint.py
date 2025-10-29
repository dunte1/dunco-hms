import requests

BASE_URL = "http://localhost:8001"
USERNAME = "admin@example.com"
PASSWORD = "password"
TIMEOUT = 30

def get_auth_token(email, password):
    url = f"{BASE_URL}/login"
    try:
        response = requests.post(url, json={"email": email, "password": password}, timeout=TIMEOUT)
    except requests.RequestException as e:
        assert False, f"Login request failed: {e}"

    assert response.status_code == 200, f"Login failed, status code {response.status_code}"

    try:
        data = response.json()
    except ValueError:
        assert False, "Login response is not valid JSON"

    token = data.get('token') or data.get('access_token')
    assert token, "No auth token found in login response"

    return token

def test_get_all_doctors_endpoint():
    token = get_auth_token(USERNAME, PASSWORD)

    url = f"{BASE_URL}/hms/doctors"
    headers = {
        "Accept": "application/json",
        "Authorization": f"Bearer {token}"
    }
    try:
        response = requests.get(url, headers=headers, timeout=TIMEOUT)
    except requests.RequestException as e:
        assert False, f"Request to get all doctors failed: {e}"

    # Assert status code
    assert response.status_code == 200, f"Expected status code 200, got {response.status_code}"

    # Assert content-type
    content_type = response.headers.get("Content-Type", "")
    assert "application/json" in content_type, f"Expected JSON response, got Content-Type: {content_type}"

    try:
        data = response.json()
    except ValueError:
        assert False, "Response is not valid JSON"

    # Assert response is a list (or dict with list)
    assert isinstance(data, list), f"Expected response to be a list of doctors, got {type(data)}"

    if len(data) > 0:
        # Check doctor data format for first item
        doctor = data[0]
        expected_fields = {
            "first_name": str,
            "last_name": str,
            "email": str,
            "phone": str,
            "department_id": int,
            "specialization": str,
        }
        for field, field_type in expected_fields.items():
            assert field in doctor, f"Doctor object missing expected field: {field}"
            # None type could be possible, so check only if not None
            if doctor[field] is not None:
                if field_type == int:
                    assert isinstance(doctor[field], int), f"Field '{field}' expected type int, got {type(doctor[field])}"
                else:
                    assert isinstance(doctor[field], str), f"Field '{field}' expected type str, got {type(doctor[field])}"

test_get_all_doctors_endpoint()
