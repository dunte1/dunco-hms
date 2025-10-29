import requests

BASE_URL = "http://localhost:8001"
TIMEOUT = 30


def test_create_new_doctor_endpoint():
    url = f"{BASE_URL}/hms/doctors"
    headers = {
        "Content-Type": "application/json",
        "Accept": "application/json"
    }

    # Sample valid doctor data for creation
    doctor_data = {
        "first_name": "John",
        "last_name": "Doe",
        "email": "john.doe.test@example.com",
        "phone": "+1234567890",
        "department_id": 1,
        "specialization": "Cardiology"
    }

    created_doctor_id = None

    try:
        # Create new doctor
        response = requests.post(url, json=doctor_data, headers=headers, timeout=TIMEOUT)
        assert response.status_code == 201 or response.status_code == 200, f"Expected status 201 or 200, got {response.status_code}"
        json_resp = response.json()
        # Check expected successful response structure and data presence
        assert "id" in json_resp or "data" in json_resp, "Response JSON missing 'id' or 'data' key"
        # Extract doctor id for cleanup
        if "id" in json_resp:
            created_doctor_id = json_resp["id"]
        elif "data" in json_resp and "id" in json_resp["data"]:
            created_doctor_id = json_resp["data"]["id"]
        else:
            # fallback: try to find id in the json response keys
            possible_ids = [v for k, v in json_resp.items() if k.lower() == "id"]
            if possible_ids:
                created_doctor_id = possible_ids[0]

        # Additional asserts to ensure fields match
        for key in doctor_data:
            # Sometimes response may return as nested under 'data'
            val = json_resp.get(key)
            if val is None and "data" in json_resp:
                val = json_resp["data"].get(key)
            assert val == doctor_data[key], f"Mismatch in field '{key}': expected '{doctor_data[key]}', got '{val}'"

    finally:
        # Cleanup: delete the created doctor if created
        if created_doctor_id:
            delete_url = f"{url}/{created_doctor_id}"
            try:
                del_response = requests.delete(delete_url, timeout=TIMEOUT)
                assert del_response.status_code in (200, 204, 202), f"Expected successful delete status (200,202,204), got {del_response.status_code}"
            except Exception:
                pass


test_create_new_doctor_endpoint()
