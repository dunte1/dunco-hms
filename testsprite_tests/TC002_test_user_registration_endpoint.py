import requests
import uuid

BASE_URL = "http://localhost:8001"
REGISTER_ENDPOINT = f"{BASE_URL}/register"
LOGIN_ENDPOINT = f"{BASE_URL}/login"
HEADERS = {"Content-Type": "application/json"}
TIMEOUT = 30

def test_user_registration_endpoint():
    # Attempt to register a new user with valid data
    unique_email = f"testuser_{uuid.uuid4().hex[:8]}@example.com"
    registration_payload = {
        "name": "Test User",
        "email": unique_email,
        "password": "StrongPassw0rd!",
        "password_confirmation": "StrongPassw0rd!"
    }
    try:
        response = requests.post(
            REGISTER_ENDPOINT,
            json=registration_payload,
            headers=HEADERS,
            timeout=TIMEOUT
        )
    except requests.RequestException as e:
        assert False, f"Request to /register failed: {e}"
    
    # Assert that registration was successful (commonly 201 or 200)
    assert response.status_code in (200, 201), f"Expected 200 or 201, got {response.status_code}. Response: {response.text}"

    # Check that response body indicates success with user info or token
    try:
        resp_json = response.json()
    except ValueError:
        assert False, "Response is not a valid JSON"

    # Typical fields to check: user id, name, email or success message
    assert "name" in resp_json or "user" in resp_json, "Response JSON does not contain expected registration confirmation"

    # Test error case: registration with existing email
    try:
        response_duplicate = requests.post(
            REGISTER_ENDPOINT,
            json=registration_payload,
            headers=HEADERS,
            timeout=TIMEOUT
        )
    except requests.RequestException as e:
        assert False, f"Request to /register for duplicate email failed: {e}"

    # Should return an error status such as 422 Unprocessable Entity or 400 Bad Request
    assert response_duplicate.status_code in (400, 422), f"Expected 400 or 422 on duplicate registration, got {response_duplicate.status_code}"

    # Error response should contain validation errors
    try:
        error_json = response_duplicate.json()
    except ValueError:
        assert False, "Error response is not a valid JSON"

    # Check error message keys typically returned in Laravel for validation
    error_keys = ["errors", "message"]
    assert any(key in error_json for key in error_keys), "Error response JSON does not contain expected validation keys"

test_user_registration_endpoint()