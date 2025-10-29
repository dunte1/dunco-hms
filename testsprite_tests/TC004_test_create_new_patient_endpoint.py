import requests
from requests.auth import HTTPBasicAuth
import uuid

BASE_URL = "http://localhost:8001"
AUTH_USERNAME = "admin@example.com"
AUTH_PASSWORD = "password"
TIMEOUT = 30

def test_create_new_patient_endpoint():
    url = f"{BASE_URL}/hms/patients"
    headers = {
        "Content-Type": "application/json",
        "Accept": "application/json"
    }
    # Generate unique email to avoid conflict
    unique_email = f"patient_{uuid.uuid4().hex[:8]}@example.com"
    payload = {
        "first_name": "John",
        "last_name": "Doe",
        "email": unique_email,
        "phone": "1234567890",
        "date_of_birth": "1990-01-01",
        "gender": "Male",
        "address": "123 Main St, Cityville"
    }

    patient_id = None
    try:
        response = requests.post(
            url,
            auth=HTTPBasicAuth(AUTH_USERNAME, AUTH_PASSWORD),
            headers=headers,
            json=payload,
            timeout=TIMEOUT
        )
        # Check HTTP status code
        assert response.status_code == 201 or response.status_code == 200, f"Expected 200 or 201, got {response.status_code}"
        json_response = response.json()
        # Basic validation on response structure
        assert isinstance(json_response, dict), "Response is not a JSON object"
        # Expect some success indication or returned patient data with an id
        if "id" in json_response:
            patient_id = json_response.get("id")
        elif "data" in json_response and isinstance(json_response["data"], dict):
            patient_id = json_response["data"].get("id")
        assert patient_id is not None, "Response JSON does not contain patient id"

        # Validate returned fields match submitted data where applicable
        response_data = json_response.get("data", json_response)
        assert response_data.get("first_name") == payload["first_name"]
        assert response_data.get("last_name") == payload["last_name"]
        assert response_data.get("email") == payload["email"]
        assert response_data.get("phone") == payload["phone"]
        assert response_data.get("date_of_birth") == payload["date_of_birth"]
        assert response_data.get("gender") == payload["gender"]
        assert response_data.get("address") == payload["address"]

    finally:
        # Clean up: delete the created patient if patient_id was obtained
        if patient_id:
            delete_url = f"{BASE_URL}/hms/patients/{patient_id}"
            try:
                del_resp = requests.delete(
                    delete_url,
                    auth=HTTPBasicAuth(AUTH_USERNAME, AUTH_PASSWORD),
                    headers={"Accept": "application/json"},
                    timeout=TIMEOUT
                )
                # Accept 200 or 204 as success deletion response
                assert del_resp.status_code in (200, 204), f"Failed to delete patient, status code {del_resp.status_code}"
            except Exception:
                pass

test_create_new_patient_endpoint()
