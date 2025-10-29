import requests

BASE_URL = "http://localhost:8001"
TIMEOUT = 30

# Login function to get session cookie and CSRF token

def login(email, password):
    login_url = f"{BASE_URL}/login"
    session = requests.Session()

    # Retrieve the CSRF cookie by GET /login
    get_resp = session.get(login_url, timeout=TIMEOUT)
    assert get_resp.status_code == 200, f"Failed to load login page: {get_resp.text}"

    # Extract CSRF token from cookies
    csrf_token = session.cookies.get('XSRF-TOKEN')
    assert csrf_token is not None, "CSRF token not found in cookies"

    login_payload = {
        "email": email,
        "password": password
    }
    headers = {
        "Content-Type": "application/json",
        "X-XSRF-TOKEN": csrf_token
    }

    resp = session.post(login_url, json=login_payload, headers=headers, timeout=TIMEOUT)
    assert resp.status_code == 200, f"Login failed: {resp.text}"
    return session


def test_create_new_invoice_endpoint():
    headers = {
        "Content-Type": "application/json"
    }

    # Authenticate and get session
    session = login("admin@example.com", "password")

    # Step 1: Create a patient to use patient_id
    patient_payload = {
        "first_name": "Test",
        "last_name": "Patient",
        "email": "test_patient@example.com",
        "phone": "1234567890",
        "date_of_birth": "1990-01-01",
        "gender": "Other",
        "address": "123 Test St"
    }

    patient_id = None
    invoice_id = None

    try:
        patient_resp = session.post(
            f"{BASE_URL}/hms/patients",
            json=patient_payload,
            headers=headers,
            timeout=TIMEOUT
        )
        assert patient_resp.status_code in [200, 201], f"Failed to create patient: {patient_resp.text}"
        patient_data = patient_resp.json()
        assert "id" in patient_data, "Patient response JSON missing 'id'"
        patient_id = patient_data["id"]

        # Step 2: Create a new invoice with the created patient_id
        invoice_payload = {
            "patient_id": patient_id,
            "total_amount": 1500.75,
            "items": [
                {
                    "description": "X-Ray",
                    "quantity": 1,
                    "unit_price": 500.25
                },
                {
                    "description": "Blood Test",
                    "quantity": 1,
                    "unit_price": 1000.50
                }
            ]
        }

        invoice_resp = session.post(
            f"{BASE_URL}/hms/invoices",
            json=invoice_payload,
            headers=headers,
            timeout=TIMEOUT
        )
        assert invoice_resp.status_code in [200, 201], f"Failed to create invoice: {invoice_resp.text}"

        invoice_data = invoice_resp.json()
        assert "id" in invoice_data, "Invoice response JSON missing 'id'"
        assert invoice_data.get("patient_id") == patient_id, "Invoice patient_id mismatch"
        assert abs(invoice_data.get("total_amount", 0) - 1500.75) < 0.01, "Invoice total_amount mismatch"
        assert isinstance(invoice_data.get("items"), list) and len(invoice_data["items"]) == 2, "Invoice items mismatch"

        invoice_id = invoice_data["id"]

    finally:
        # Clean up: delete created invoice and patient if possible
        if invoice_id is not None:
            try:
                del_invoice_resp = session.delete(
                    f"{BASE_URL}/hms/invoices/{invoice_id}",
                    timeout=TIMEOUT
                )
                assert del_invoice_resp.status_code in [200, 204]
            except Exception:
                pass

        if patient_id is not None:
            try:
                del_patient_resp = session.delete(
                    f"{BASE_URL}/hms/patients/{patient_id}",
                    timeout=TIMEOUT
                )
                assert del_patient_resp.status_code in [200, 204]
            except Exception:
                pass


test_create_new_invoice_endpoint()
