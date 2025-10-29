import requests
from requests.auth import HTTPBasicAuth

BASE_URL = "http://localhost:8001"
AUTH = HTTPBasicAuth("admin@example.com", "password")
TIMEOUT = 30

def test_get_all_invoices_endpoint():
    url = f"{BASE_URL}/hms/invoices"
    headers = {
        "Accept": "application/json"
    }
    try:
        response = requests.get(url, auth=AUTH, headers=headers, timeout=TIMEOUT)
    except requests.RequestException as e:
        assert False, f"Request failed: {e}"

    assert response.status_code == 200, f"Expected status code 200 but got {response.status_code}"
    
    try:
        data = response.json()
    except ValueError:
        assert False, "Response is not valid JSON"

    assert isinstance(data, list), f"Expected response to be a list but got {type(data)}"

    # Validate invoice data format if list is not empty
    if len(data) > 0:
        invoice = data[0]
        # Minimum expected fields for invoice based on PRD schema
        expected_keys = {"patient_id", "total_amount", "items"}
        assert all(key in invoice for key in expected_keys), f"Invoice missing expected keys. Found keys: {invoice.keys()}"
        assert isinstance(invoice["patient_id"], int), "patient_id should be an integer"
        assert isinstance(invoice["total_amount"], (int, float)), "total_amount should be a number"
        assert isinstance(invoice["items"], list), "items should be a list"

test_get_all_invoices_endpoint()