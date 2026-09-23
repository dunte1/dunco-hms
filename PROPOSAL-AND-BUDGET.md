# DUNCO HOSPITAL MANAGEMENT SYSTEM (Dunco HMS)
## Comprehensive Implementation Proposal & Budget

**Version:** 2.4
**Date:** September 21, 2026
**Prepared by:** Dunco Web Solutions
**Production URL:** https://hmse.duncowebsolutions.co.ke/

---

## TABLE OF CONTENTS

1. [Executive Summary](#1-executive-summary)
2. [Current System Analysis](#2-current-system-analysis)
3. [Implementation Scope](#3-implementation-scope)
4. [Hosting & Domain](#4-hosting--domain)
5. [Installation & Setup](#5-installation--setup)
6. [User Training](#6-user-training)
7. [Support & Maintenance](#7-support--maintenance)
8. [User Adjustments & Recommendations](#8-user-adjustments--recommendations)
9. [Budget Summary](#9-budget-summary)
10. [Payment Terms](#10-payment-terms)
11. [Timeline](#11-timeline)

---

## 1. EXECUTIVE SUMMARY

Dunco HMS is a comprehensive, production-ready Hospital Management System with **130+ features** covering clinical, financial, administrative, and operational workflows. The system is built on Laravel 12 / PHP 8.2+ with MySQL, featuring AI assistants, telemedicine, M-Pesa integration, Kenya SHA/DHA insurance integration, RFID/IoT monitoring, and a full marketing suite.

This proposal covers the complete deployment, setup, training, support, and ongoing maintenance required to get the system fully operational for a hospital/clinic.

---

## 2. CURRENT SYSTEM ANALYSIS

### What's Already Built (Complete)
| Area | Status | Details |
|------|--------|---------|
| Patient Management | ✅ Complete | Registration, profiles, history, search |
| Doctor Management | ✅ Complete | Profiles, departments, scheduling |
| Appointments | ✅ Complete | Booking, online requests, AI suggestions |
| OPD / IPD | ✅ Complete | Outpatient & Inpatient management |
| Pharmacy | ✅ Complete | Medicine catalog, prescriptions, stock, e-prescription |
| Laboratory | ✅ Complete | Tests, requests, reports, equipment integration |
| Radiology | ✅ Complete | Tests, requests, PDF reports |
| Billing & Finance | ✅ Complete | Invoices, payments (M-Pesa/Stripe/PayPal), receipts, ledger |
| Insurance/SHA | ✅ Complete | Claims, eligibility, pre-auth (credentials needed) |
| HR & Payroll | ✅ Complete | Employees, attendance, leave, payroll, appraisals |
| Blood Bank | ✅ Complete | Donors, inventory, requests |
| Ambulance | ✅ Complete | Fleet management, call tracking |
| Operating Theatre | ✅ Complete | Scheduling, room management, sterilization |
| Nursing | ✅ Complete | Duty roster, ward assignment |
| AI Features | ✅ Complete | Elliana-D virtual nurse, diagnosis suggestions |
| Telemedicine | ✅ Complete | Video consultations (Zoom integration) |
| Patient Portal | ✅ Complete | Self-service booking, results, billing |
| Marketing Module | ✅ Complete | Campaigns, social media, SEO, AI content |
| RFID/IoT | ✅ Complete | Tag management, bed sensors |
| CMS | ✅ Complete | Blog, gallery, careers, testimonials |
| Multi-Language | ✅ Complete | English, French, Swahili, Arabic |
| Multi-Branch | ✅ Complete | Branch management |
| Reports & Analytics | ✅ Complete | BI dashboard, custom reports, Excel/PDF export |

### What Needs Configuration (Credentials/Setup)
| Item | Status | Action Required |
|------|--------|-----------------|
| M-Pesa (Safaricom) | ⚙️ Sandbox | Need production credentials from Safaricom |
| SHA/EHA Integration | ⚙️ Code Ready | Need DHA credentials from Kenya Ministry of Health |
| Twilio SMS | ⚙️ Code Ready | Need Twilio account credentials |
| Zoom Telemedicine | ⚙️ Code Ready | Need Zoom API credentials |
| Stripe Payments | ⚙️ Code Ready | Need Stripe account credentials |
| PayPal Payments | ⚙️ Code Ready | Need PayPal account credentials |
| SMTP Email | ⚙️ Configured | Need production email server credentials |

---

## 3. IMPLEMENTATION SCOPE

### Phase 1: Infrastructure Setup (Week 1)
- Domain registration and DNS configuration
- Production server provisioning and configuration
- SSL certificate installation (HTTPS)
- MySQL database setup and configuration
- PHP 8.2+ and required extensions installation
- Apache/Nginx web server configuration
- Redis setup for caching and sessions (recommended)
- Automated backup configuration

### Phase 2: Application Deployment (Week 1-2)
- Laravel application deployment to production server
- Environment configuration (.env production settings)
- Database migration execution (165 migrations)
- Database seeding (roles, permissions, ICD-10 codes, demo data)
- Vite asset compilation and deployment
- Storage permissions and symlink configuration
- Queue worker setup (for background jobs)
- Cron job configuration (scheduled tasks)

### Phase 3: Third-Party Integration (Week 2-3)
- M-Pesa production integration and testing
- SMTP email server configuration and testing
- SMS gateway setup (Twilio/Africa's Talking)
- Zoom API integration for telemedicine
- SHA/DHA integration configuration (when credentials available)
- Payment gateway testing (Stripe/PayPal if required)
- AI service verification (OpenRouter)

### Phase 4: User Training (Week 3-5)
- Administrator training sessions
- Department-specific user training
- Documentation and quick-reference guides
- Hands-on practice sessions

### Phase 5: Go-Live & Support (Week 5-6)
- Final testing and quality assurance
- Data migration assistance (if migrating from existing system)
- Go-live support and monitoring
- Post-launch bug fixes and adjustments

---

## 4. HOSTING & DOMAIN

### Recommended Hosting Configuration

| Component | Specification | Monthly Cost (KES) |
|-----------|---------------|---------------------|
| **Domain Name** | .co.ke or .com (1 year) | KES 2,500/year |
| **Cloud Server (VPS)** | 4 vCPU, 8GB RAM, 100GB SSD | KES 8,000 |
| **SSL Certificate** | Let's Encrypt (Free) | KES 0 |
| **Backup Storage** | Automated daily backups (10GB) | KES 1,500 |
| **Server Management** | OS updates, security patches | KES 2,000 |
| **CDN (Optional)** | Cloudflare free tier | KES 0 |

### Hosting Provider Recommendations
| Provider | Plan | Monthly Cost | Notes |
|----------|------|--------------|-------|
| **Contabo VPS** | Cloud VPS S | ~KES 5,500 | Good value, Germany/US |
| **DigitalOcean** | Basic Droplet 8GB | ~KES 8,000 | Reliable, good docs |
| **Hetzner** | CPX41 | ~KES 6,000 | Excellent performance |
| **AWS Lightsail** | 8GB plan | ~KES 9,500 | Scalable, enterprise |
| **Safaricom Cloud** | SME Plan | ~KES 10,000 | Local, Kenya-based |

### Business Email (Free Unlimited)

| Provider | Plan | Cost | Features |
|----------|------|------|----------|
| **Zoho Mail** | Mail Lite | FREE | 5GB/user, custom domain, unlimited users |
| **Google Workspace** | Business Starter | ~KES 800/user/mo | 30GB, Gmail, Meet, Calendar |
| **Microsoft 365** | Business Basic | ~KES 900/user/mo | 1TB, Outlook, Teams |

**Recommended:** Zoho Mail (free tier) for unlimited business emails with custom domain
- Create emails like: admin@hospital.co.ke, doctor@hospital.co.ke, pharmacy@hospital.co.ke
- IMAP/POP3 support, webmail, mobile sync
- No per-user cost on free plan

### Monthly Hosting Budget
| Item | Monthly (KES) | Annual (KES) |
|------|---------------|--------------|
| Domain Registration | - | KES 2,500 |
| VPS Server | KES 8,000 | KES 96,000 |
| SSL Certificate | KES 0 | KES 0 |
| Backups | KES 1,500 | KES 18,000 |
| Server Management | KES 2,000 | KES 24,000 |
| Business Email (Zoho) | KES 0 | KES 0 |
| **TOTAL** | **KES 11,500/mo** | **KES 140,500/yr** |

---

## 5. INSTALLATION & SETUP

### Server Setup Tasks
| Task | Description | Hours | Cost (KES) |
|------|-------------|-------|------------|
| OS Installation | Ubuntu 22.04 LTS setup & hardening | 2 | 4,000 |
| Web Server | Apache/Nginx + PHP 8.2 + required extensions | 3 | 6,000 |
| Database | MySQL 8.0 setup, tuning, security | 2 | 4,000 |
| Redis | Cache & session store setup | 1 | 2,000 |
| SSL/HTTPS | Certificate installation & auto-renewal | 1 | 2,000 |
| Firewall | UFW/firewall rules, fail2ban | 1 | 2,000 |
| Email | SMTP server or relay configuration | 2 | 4,000 |
| Cron Jobs | Scheduled tasks setup | 1 | 2,000 |
| Queue Worker | Laravel queue daemon setup (Supervisor) | 1 | 2,000 |
| Backup System | Automated daily backups + offsite | 2 | 4,000 |
| Monitoring | Server uptime monitoring setup | 1 | 2,000 |
| **Subtotal** | | **17 hrs** | **KES 34,000** |

### Application Deployment Tasks
| Task | Description | Hours | Cost (KES) |
|------|-------------|-------|------------|
| Code Deployment | Git clone, dependency install, env config | 2 | 4,000 |
| Database Migration | Run 165 migrations + seeders | 1 | 2,000 |
| Asset Build | Vite production build + optimization | 1 | 2,000 |
| Storage Setup | Symlinks, permissions, directories | 1 | 2,000 |
| M-Pesa Config | Production credentials integration | 2 | 4,000 |
| SMS Config | Twilio/Africa's Talking setup | 2 | 4,000 |
| Email Config | Transactional email setup (Mail/SMTP) | 2 | 4,000 |
| Zoom Config | Telemedicine API integration | 2 | 4,000 |
| AI Config | OpenRouter API verification | 1 | 2,000 |
| Payment Config | Stripe/PayPal setup (if needed) | 2 | 4,000 |
| Testing | Full system testing across all modules | 4 | 8,000 |
| **Subtotal** | | **20 hrs** | **KES 40,000** |

### Installation & Setup Total: KES 74,000 (One-time)

---

## 6. USER TRAINING

### Training Program Structure

| Session | Target Users | Duration | Topics | Cost (KES) |
|---------|-------------|----------|--------|------------|
| **Admin Training** | System Admins, IT Staff | 3 days (6 hrs/day) | Full system config, roles, permissions, backups, settings | 45,000 |
| **Reception/Front Desk** | Receptionists | 2 days (6 hrs/day) | Patient registration, appointments, queue management | 20,000 |
| **Doctor Training** | Doctors/Clinicians | 1 day (6 hrs/day) | Appointments, OPD/IPD, prescriptions, lab orders, telemedicine | 15,000 |
| **Nurse Training** | Nurses | 1 day (6 hrs/day) | Ward management, vitals, duty roster, nursing notes | 15,000 |
| **Pharmacy Training** | Pharmacists | 2 days (6 hrs/day) | Medicine catalog, prescriptions, stock, suppliers, dispensing | 20,000 |
| **Lab Training** | Lab Technicians | 1 day (6 hrs/day) | Lab requests, test results, equipment, reports | 15,000 |
| **Billing Training** | Accountants/Cashiers | 2 days (6 hrs/day) | Invoicing, payments, M-Pesa, receipts, financial reports | 20,000 |
| **HR Training** | HR Staff | 1 day (6 hrs/day) | Employee management, attendance, payroll, leave | 15,000 |
| **Insurance Training** | Insurance Desk | 1 day (6 hrs/day) | SHA integration, claims, eligibility, pre-auth | 15,000 |
| **Marketing Training** | Marketing Staff | 1 day (6 hrs/day) | CMS, social media, campaigns, blog, SEO | 15,000 |

### Training Deliverables
| Item | Description |
|------|-------------|
| Training Manual | Comprehensive user guide (printed + PDF) |
| Quick Reference Cards | Laminated cheat sheets for each role |
| Video Tutorials | Recorded sessions for on-demand learning |
| Admin Handbook | System administration guide |
| FAQ Document | Common questions and troubleshooting |
| Training Environment | Dedicated staging server for practice |

### Training Total: KES 195,000 (One-time)

---

## 7. SUPPORT & MAINTENANCE

### Support Tiers

| Tier | Coverage | Response Time | Monthly Cost (KES) |
|------|----------|---------------|---------------------|
| **Basic** | Email support (Mon-Fri, 9am-5pm) | 24 hours | 15,000 |
| **Standard** | Email + Phone + Remote Desktop (Mon-Fri, 8am-6pm) | 4 hours | 30,000 |
| **Premium** | 24/7 Email + Phone + Remote + On-site visits | 1 hour | 60,000 |

### Maintenance Services (Included in all tiers)
| Service | Frequency | Description |
|---------|-----------|-------------|
| Server Monitoring | Continuous | Uptime, CPU, RAM, disk monitoring |
| Security Updates | Monthly | OS and software patches |
| Application Updates | Monthly | Laravel security patches, dependency updates |
| Database Optimization | Monthly | Query optimization, index maintenance |
| Backup Verification | Weekly | Test restore, integrity checks |
| Performance Reports | Monthly | System health and usage reports |
| SSL Renewal | Auto/Renewal | Let's Encrypt auto-renewal |
| Malware Scanning | Weekly | Security vulnerability scanning |

### Additional Support Services (Optional)
| Service | Description | Cost (KES) |
|---------|-------------|------------|
| On-site Training | Additional training sessions | 15,000/day |
| Custom Feature Development | New features or modifications | 5,000/hr |
| Data Migration | Import data from old system | 50,000-150,000 |
| System Integration | Connect with other hospital systems | 75,000+ |
| Performance Tuning | Advanced optimization | 25,000 |
| Security Audit | Comprehensive security review | 40,000 |
| Disaster Recovery Setup | Multi-server failover | 100,000+ |

### Recommended: Standard Support Plan
**Monthly: KES 30,000** | **Annual: KES 360,000** (save 10% = KES 324,000)

---

## 8. USER ADJUSTMENTS & RECOMMENDATIONS

### Critical Recommendations

| Priority | Recommendation | Reason | Est. Cost (KES) |
|----------|---------------|--------|-----------------|
| 🔴 HIGH | **Enable Redis for caching** | Improves performance 3-5x, reduces DB load | 5,000 setup |
| 🔴 HIGH | **Configure production M-Pesa** | Enable mobile payments (critical for Kenya) | Included in setup |
| 🔴 HIGH | **Enable HTTPS everywhere** | Security compliance, patient data protection | Free (Let's Encrypt) |
| 🔴 HIGH | **Set up automated backups** | Prevent data loss, regulatory compliance | Included in setup |
| 🟡 MED | **Enable 2FA for admin accounts** | Enhanced security for privileged users | Free (built-in) |
| 🟡 MED | **Configure SMS notifications** | Appointment reminders, payment alerts | 5,000 setup |
| 🟡 MED | **Set up email notifications** | Invoice delivery, report sharing | Included in setup |
| 🟡 MED | **Enable audit logging** | Track all system changes for compliance | Free (built-in) |
| 🟢 LOW | **Configure Zoom telemedicine** | Enable remote consultations | 3,000 setup |
| 🟢 LOW | **Set up marketing emails** | Patient engagement, health campaigns | Free (Mailchimp tier) |
| 🟢 LOW | **Enable multi-language (Swahili)** | Local language support for staff | Free (built-in) |
| 🟢 LOW | **Configure AI assistant (Elliana-D)** | Virtual nurse for patient queries | Free (OpenRouter) |

### User-Specific Adjustments

| Adjustment | Description | Cost (KES) |
|------------|-------------|------------|
| **Branding & Theming** | Custom hospital logo, colors, branding | 15,000 |
| **Custom Reports** | Hospital-specific report templates | 20,000 |
| **Department Setup** | Configure hospital departments & wards | 10,000 |
| **Role Customization** | Custom roles beyond default 21 roles | 10,000 |
| **Fee Schedule Setup** | Configure consultation fees, service charges | 5,000 |
| **Insurance Plans** | Set up local insurance providers | 10,000 |
| **SMS Templates** | Custom SMS message templates | 5,000 |
| **Email Templates** | Custom email notification templates | 5,000 |
| **Print Templates** | Custom receipt/invoice/letterhead designs | 15,000 |
| **Data Import** | Import existing patient/staff data | 50,000-150,000 |

### Adjustments Total: KES 145,000-245,000 (One-time, depending on scope)

---

## 9. BUDGET SUMMARY

### One-Time Costs

| Category | Item | Cost (KES) |
|----------|------|------------|
| **Hosting & Domain** | Domain registration (1 year) | 2,500 |
| **Installation & Setup** | Server setup (17 hrs) | 34,000 |
| **Installation & Setup** | Application deployment (20 hrs) | 40,000 |
| **Training** | All user training (10 sessions) | 195,000 |
| **Adjustments** | Branding, theming, custom setup | 50,000 |
| **Adjustments** | Data migration (estimated) | 75,000 |
| | | |
| **SUBTOTAL (One-Time)** | | **KES 396,500** |

### Annual Recurring Costs

| Category | Item | Monthly (KES) | Annual (KES) |
|----------|------|---------------|--------------|
| **Hosting** | VPS Server + Backups + Management | 11,500 | 140,500 |
| **Support** | Standard Support Plan | 30,000 | 324,000 (10% discount) |
| **Domain** | Domain renewal | - | 2,500 |
| | | | |
| **SUBTOTAL (Annual)** | | **KES 41,500/mo** | **KES 467,000/yr** |

### Total Investment Summary

| Period | Cost (KES) | Cost (USD ~) |
|--------|------------|--------------|
| **First Year (Total)** | **KES 863,500** | **~$6,640** |
| **Annual Renewal (Year 2+)** | **KES 467,000** | **~$3,590** |

*USD conversion at approximately KES 130 = $1*

### Budget Breakdown by Category

```
Installation & Setup:     KES 74,000   (8.6%)  ████░░░░░░░░░░░░
Training:                 KES 195,000  (22.6%) ██████████░░░░░░
Hosting (Year 1):         KES 140,500  (16.3%) ████████░░░░░░░░
Support (Year 1):         KES 324,000  (37.5%) ████████████████
Adjustments & Migration:  KES 125,000  (14.5%) ███████░░░░░░░░░
Domain:                   KES 5,000    (0.6%)  ░░░░░░░░░░░░░░░░
────────────────────────────────────────────────
TOTAL (First Year):       KES 863,500  (100%)
```

---

## 10. PAYMENT TERMS

| Milestone | Percentage | Amount (KES) | Due Date |
|-----------|------------|--------------|----------|
| Project Kickoff | 30% | KES 259,050 | Upon signing |
| Infrastructure Ready | 25% | KES 215,875 | Week 2 |
| Training Complete | 25% | KES 215,875 | Week 5 |
| Go-Live Acceptance | 20% | KES 172,700 | Week 6 |

### Payment Methods
- Bank Transfer (KES)
- M-Pesa (Paybill)
- Cash (with receipt)

---

## 11. TIMELINE

```
Week 1:  ██████████████  Infrastructure Setup + Domain + Server
Week 2:  ██████████████  Application Deployment + Database + Testing
Week 3:  ██████████████  Third-party Integration (M-Pesa, SMS, Email)
Week 4:  ██████████████  User Training (Admin, Reception, Doctors)
Week 5:  ██████████████  User Training (Pharmacy, Lab, Billing, HR)
Week 6:  ██████████████  Final Testing + Go-Live + Support Handover
```

**Total Duration: 6 weeks from project kickoff**

---

## 12. TERMS & CONDITIONS

1. All prices are in Kenya Shillings (KES) and exclusive of VAT where applicable
2. Payment is due within 7 days of each milestone
3. Support hours: Monday-Friday, 8:00 AM - 6:00 EAT (Standard plan)
4. On-site support within Nairobi: included in Premium plan; additional for other regions
5. Custom feature development billed separately at KES 5,000/hour
6. Annual hosting and support renewals due on contract anniversary
7. System ownership transfers to client upon full payment
8. Source code access provided via GitHub repository
9. 30-day warranty period after go-live for bug fixes

---

## ACCEPTANCE

By signing below, you agree to the scope, budget, and timeline outlined in this proposal.

**Client:**
Name: _______________________________
Signature: _______________________________
Date: _______________________________

**Dunco Web Solutions:**
Name: _______________________________
Signature: _______________________________
Date: _______________________________

---

*For questions or clarifications, contact:*
*Dunco Web Solutions*
*Email: dunthecan02@gmail.com*
*GitHub: https://github.com/dunte1/dunco-hms*
*Production: https://hmse.duncowebsolutions.co.ke/*
