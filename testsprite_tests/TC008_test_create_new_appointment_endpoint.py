import requests
from requests.auth import HTTPBasicAuth
from datetime import datetime, timedelta

BASE_URL = "http://localhost:8001"
AUTH_USERNAME = "admin@example.com"
AUTH_PASSWORD = "password"
TIMEOUT = 30


def test_create_new_appointment_endpoint():
    auth = HTTPBasicAuth(AUTH_USERNAME, AUTH_PASSWORD)
    headers = {
        "Content-Type": "application/json",
        "Accept": "application/json",
    }

    # Helper function to create a patient
    def create_patient():
        patient_data = {
            "first_name": "Test",
            "last_name": "Patient",
            "email": f"test.patient.{int(datetime.utcnow().timestamp())}@example.com",
            "phone": "1234567890",
            "date_of_birth": "1990-01-01",
            "gender": "Male",
            "address": "123 Test St"
        }
        response = requests.post(f"{BASE_URL}/hms/patients", json=patient_data, auth=auth, headers=headers, timeout=TIMEOUT)
        response.raise_for_status()
        return response.json().get("id") or response.json().get("data", {}).get("id") or response.json().get("patient", {}).get("id") or response.json().get("patient_id") or response.json().get("patientId") or response.json().get("patient_id")  # try common keys

    # Helper function to create a doctor
    def create_doctor():
        # To create a doctor we need department_id - get one if exists or create dummy data
        # First get doctors departments if possible
        try:
            dept_resp = requests.get(f"{BASE_URL}/hms/doctor-departments", auth=auth, headers=headers, timeout=TIMEOUT)
            dept_resp.raise_for_status()
            departments = dept_resp.json()
            # Try to find an integer id from departments list
            if isinstance(departments, dict):
                # sometimes wrapped under data key
                data = departments.get("data") or departments.get("departments") or departments.get("doctor_departments") or departments.get("items")
                if data and isinstance(data, list) and len(data) > 0:
                    department_id = data[0].get("id") if isinstance(data[0], dict) else None
                else:
                    department_id = None
            elif isinstance(departments, list) and len(departments) > 0:
                department_id = departments[0].get("id")
            else:
                department_id = None
        except Exception:
            department_id = None

        # If no department found, fallback to 1 (may fail)
        if not department_id:
            department_id = 1

        doctor_data = {
            "first_name": "Test",
            "last_name": "Doctor",
            "email": f"test.doctor.{int(datetime.utcnow().timestamp())}@example.com",
            "phone": "0987654321",
            "department_id": department_id,
            "specialization": "General"
        }
        response = requests.post(f"{BASE_URL}/hms/doctors", json=doctor_data, auth=auth, headers=headers, timeout=TIMEOUT)
        response.raise_for_status()
        return response.json().get("id") or response.json().get("data", {}).get("id") or response.json().get("doctor", {}).get("id") or response.json().get("doctor_id") or response.json().get("doctorId")

    # Create patient and doctor resources used for appointment
    patient_id = None
    doctor_id = None
    appointment_id = None

    try:
        patient_id = create_patient()
        assert patient_id is not None, "Failed to create patient for appointment"

        doctor_id = create_doctor()
        assert doctor_id is not None, "Failed to create doctor for appointment"

        # Prepare appointment date and time
        appointment_date = (datetime.utcnow() + timedelta(days=1)).strftime("%Y-%m-%d")
        appointment_time = "10:00"

        appointment_data = {
            "patient_id": patient_id,
            "doctor_id": doctor_id,
            "appointment_date": appointment_date,
            "appointment_time": appointment_time,
            "reason": "Routine Checkup"
        }

        resp = requests.post(f"{BASE_URL}/hms/appointments", json=appointment_data, auth=auth, headers=headers, timeout=TIMEOUT)
        assert resp.status_code == 201 or resp.status_code == 200
        resp_json = resp.json()
        # Validate response contains new appointment id or relevant data
        appointment_id = resp_json.get("id") or resp_json.get("data", {}).get("id") or resp_json.get("appointment", {}).get("id")
        assert appointment_id is not None, "Response does not contain appointment id"
        # Validate fields in response if present
        if isinstance(resp_json, dict):
            for key in ["patient_id", "doctor_id", "appointment_date", "appointment_time", "reason"]:
                if key in resp_json:
                    assert resp_json[key] == appointment_data[key]
                elif "data" in resp_json and key in resp_json["data"]:
                    assert resp_json["data"][key] == appointment_data[key]
                elif "appointment" in resp_json and key in resp_json["appointment"]:
                    assert resp_json["appointment"][key] == appointment_data[key]

    except requests.exceptions.RequestException as e:
        assert False, f"Request failed: {e}"

    finally:
        # Cleanup - delete the appointment, patient and doctor if IDs exist
        if appointment_id:
            try:
                delete_resp = requests.delete(f"{BASE_URL}/hms/appointments/{appointment_id}", auth=auth, headers=headers, timeout=TIMEOUT)
                if delete_resp.status_code not in [200, 204, 202, 404]:
                    raise Exception(f"Failed to delete appointment id {appointment_id}")
            except Exception:
                pass
        if patient_id:
            try:
                del_patient_resp = requests.delete(f"{BASE_URL}/hms/patients/{patient_id}", auth=auth, headers=headers, timeout=TIMEOUT)
                if del_patient_resp.status_code not in [200, 204, 202, 404]:
                    raise Exception(f"Failed to delete patient id {patient_id}")
            except Exception:
                pass
        if doctor_id:
            try:
                del_doctor_resp = requests.delete(f"{BASE_URL}/hms/doctors/{doctor_id}", auth=auth, headers=headers, timeout=TIMEOUT)
                if del_doctor_resp.status_code not in [200, 204, 202, 404]:
                    raise Exception(f"Failed to delete doctor id {doctor_id}")
            except Exception:
                pass


test_create_new_appointment_endpoint()