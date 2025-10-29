import requests

BASE_URL = "http://localhost:8001"
AUTH_EMAIL = "admin@example.com"
AUTH_PASSWORD = "password"
TIMEOUT = 30

def get_auth_token():
    url = f"{BASE_URL}/login"
    headers = {"Accept": "application/json"}
    payload = {
        "email": AUTH_EMAIL,
        "password": AUTH_PASSWORD
    }
    try:
        response = requests.post(url, json=payload, headers=headers, timeout=TIMEOUT)
        response.raise_for_status()
    except requests.RequestException as e:
        assert False, f"Authentication request failed: {e}"

    try:
        data = response.json()
    except ValueError:
        assert False, "Authentication response is not valid JSON"

    # Expecting a token in the response to use with Bearer Auth
    # The PRD does not specify exact token format, so assume token is in 'token' key
    token = data.get('token')
    assert token is not None and isinstance(token, str), "Authentication token not found or invalid"
    return token


def test_get_all_appointments_endpoint():
    token = get_auth_token()
    url = f"{BASE_URL}/hms/appointments"
    headers = {
        "Accept": "application/json",
        "Authorization": f"Bearer {token}"
    }
    try:
        response = requests.get(url, headers=headers, timeout=TIMEOUT)
    except requests.RequestException as e:
        assert False, f"Request to get all appointments failed: {e}"

    assert response.status_code == 200, f"Expected status code 200, got {response.status_code}"

    try:
        data = response.json()
    except ValueError:
        assert False, "Response is not valid JSON"

    appointments = None
    if isinstance(data, list):
        appointments = data
    elif isinstance(data, dict):
        if 'data' in data and isinstance(data['data'], list):
            appointments = data['data']
        else:
            assert False, "Response JSON structure unexpected: expected list or dict with 'data' list"

    for appointment in appointments:
        assert isinstance(appointment, dict), "Each appointment item should be a dict"
        assert "patient_id" in appointment and isinstance(appointment["patient_id"], int), "Appointment missing valid 'patient_id'"
        assert "doctor_id" in appointment and isinstance(appointment["doctor_id"], int), "Appointment missing valid 'doctor_id'"
        assert "appointment_date" in appointment and isinstance(appointment["appointment_date"], str), "Appointment missing valid 'appointment_date'"
        assert "appointment_time" in appointment and isinstance(appointment["appointment_time"], str), "Appointment missing valid 'appointment_time'"
        if "reason" in appointment:
            assert isinstance(appointment["reason"], str), "'reason' should be a string if present"


test_get_all_appointments_endpoint()
