# Seguridad de la API

## Modelo de Autenticación: JWT (Stateless)

### Ventajas sobre sesiones tradicionales:
- **Escalabilidad**: No requiere almacenamiento en servidor
- **Cross-domain**: Funciona entre diferentes dominios
- **Descentralización**: Ideal para microservicios

### Riesgos mitigados:
1. **CSRF**: JWT en headers previene ataques CSRF
2. **XSS**: Tokens con expiración corta limitan el daño
3. **Session Hijacking**: No hay sesiones que robar

### Medidas implementadas:
- Validación de entrada con Validator
- Passwords hasheados con bcrypt
- Tokens firmados con clave secreta (JWT_SECRET)
- Expiración de tokens (configurable en config/jwt.php)
- Logging de peticiones sospechosas

### Escenarios de ataque y defensa:
- **Token robado**: El token expira automáticamente
- **Fuerza bruta**: Rate limiting (implementar en producción)
- **SQL Injection**: Eloquent ORM protege automáticamente