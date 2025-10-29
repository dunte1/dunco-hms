import requests

def test_get_all_patients():
    base_url = "http://localhost:8001"
    endpoint = "/hms/patients"
    url = base_url + endpoint
    headers = {
        "Accept": "application/json"
    }
    try:
        response = requests.get(url, headers=headers, timeout=30)
        assert response.status_code == 200, f"Expected status code 200 but got {response.status_code}"
        patients = response.json()
        assert isinstance(patients, list), "Response is not a list"
        # Validate expected patient data structure keys if list is not empty
        if patients:
            patient = patients[0]
            expected_keys = {
                "id", "first_name", "last_name", "email", "phone",
                "date_of_birth", "gender", "address"
            }
            missing_keys = expected_keys - patient.keys()
            assert not missing_keys, f"Missing keys in patient data: {missing_keys}"
    except requests.RequestException as e:
        assert False, f"Request to {url} failed: {e}"

test_get_all_patients()