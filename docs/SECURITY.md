# Dunco HMS — Security Documentation

## Security Controls Implemented

### Authentication

| Control | Implementation |
|---------|---------------|
| Authentication Method | Laravel Sanctum (API) + Session-based (Web) |
| Password Hashing | Bcrypt (Laravel default) |
| Email Verification | Built-in Laravel email verification |
| Two-Factor Authentication | Available for patient portal |
| Session Management | Database-backed sessions |
| Session Timeout | Configurable via session lifetime |
| Remember Me | Supported |

### Authorization

| Control | Implementation |
|---------|---------------|
| Role-Based Access Control | Spatie Laravel Permission v6.21 |
| Roles Defined | 21 |
| Permissions Defined | 93 |
| Middleware | RoleMiddleware + PermissionMiddleware |
| Route Protection | Per-route role/permission checks |
| View Protection | @can directives in Blade templates |

### Data Protection

| Control | Implementation |
|---------|---------------|
| SQL Injection | Laravel Eloquent ORM (parameterized queries) |
| XSS Protection | Blade template auto-escaping |
| CSRF Protection | Laravel CSRF tokens on all forms |
| Mass Assignment | $fillable arrays on all models |
| File Upload | Server-side validation |

### API Security

| Control | Implementation |
|---------|---------------|
| Authentication | Sanctum token-based |
| Rate Limiting | 60 requests/minute per user/IP |
| CORS | Configured for stateful domains |
| Input Validation | Form request validation |

### Infrastructure Security

| Control | Implementation |
|---------|---------------|
| HTTPS | SSL/TLS via Let's Encrypt |
| Secure Cookies | SESSION_SECURE_COOKIE=true |
| HTTP Only Cookies | Default Laravel behavior |
| Server Hardening | Apache with mod_rewrite |

### Audit & Monitoring

| Control | Implementation |
|---------|---------------|
| Activity Logging | Spatie ActivityLog |
| Insurance API Logs | Custom insurance_api_logs table |
| Audit Logs | audit_logs table |
| Error Logging | Laravel log channels |

## Security Recommendations

### High Priority

1. **Restrict .env file permissions** — Currently 644 (world-readable). Should be 640 or 600.
2. **Enable MFA for admin accounts** — Patient portal has 2FA; admin should too.
3. **Enforce password policy** — Minimum 8 characters, mixed case, numbers.
4. **Review API rate limits** — Current 60/min may need adjustment.
5. **Regular security updates** — Keep composer packages updated.

### Medium Priority

6. **Add security headers** — CSP, X-Frame-Options, HSTS, X-Content-Type-Options.
7. **Input validation audit** — Review all forms for comprehensive validation.
8. **File upload restrictions** — Validate file types, sizes, and scan for malware.
9. **Database encryption** — Consider encrypting sensitive fields at rest.
10. **Backup encryption** — Encrypt backup files.

### Low Priority

11. **Security scanning** — Implement automated vulnerability scanning.
12. **Penetration testing** — Conduct before production deployment to hospitals.
13. **Compliance review** — Review against Kenya Data Protection Act requirements.
14. **Incident response plan** — Document security incident procedures.

## Compliance Notes

- **HIPAA:** Not claimed. System does not meet all HIPAA requirements without additional controls.
- **Kenya Data Protection Act 2019:** Basic controls in place; formal compliance assessment needed.
- **SHA/DHA Requirements:** Technical capability exists; compliance depends on EHA credential configuration and facility registration.
