# Problemas Resueltos

## 1. Error: "Could not find driver"
**Causa**: Extensión pdo_mysql no habilitada
**Solución**: Descomentar `;extension=pdo_mysql` en php.ini

## 2. Error: "Unauthenticated" en Postman
**Causa**: Token mal formateado en headers
**Solución**: 
- Usar tipo "Bearer Token"
- Header: `Authorization: Bearer {token}`
- Header: `Accept: application/json`

## 3. Error: "Token has expired"
**Causa**: Token caducado después de TTL configurado
**Solución**: Usar endpoint `/api/refresh` para renovar token

## 4. Error en producción: CORS
**Causa**: Frontend en dominio diferente
**Solución**: Configurar `config/cors.php` adecuadamente
```

---

## 4️⃣ **Defensa y Explicación - Nivel 4**

### Prepara estas explicaciones:

1. **Flujo completo de autenticación**:
   - "El usuario envía email y password al endpoint `/api/login`"
   - "El `AuthController` valida los datos con `Validator`"
   - "Laravel busca el usuario en la DB y verifica el hash del password"
   - "Si es correcto, `Auth::guard('api')->attempt()` genera un JWT"
   - "El token se firma con `JWT_SECRET` y se devuelve al cliente"
   - "El cliente guarda el token y lo envía en el header `Authorization` en cada petición"
   - "El middleware `auth:api` verifica la firma del token sin consultar la DB"

2. **Modificaciones clave realizadas**:
   - `User.php`: Implementa `JWTSubject` con dos métodos obligatorios
   - `config/auth.php`: Guard 'api' con driver 'jwt'
   - `AuthController.php`: Lógica de login, logout, refresh y registro
   - `routes/api.php`: Rutas públicas y protegidas con middleware

3. **Por qué JWT vs Sesiones**:
   - JWT: Stateless, escalable, ideal para APIs
   - Sesiones: Stateful, servidor guarda estado, problemas cross-domain

---

# PARTE 2: DESPLIEGUE EN RENDER 🚀

## Paso 1: Preparar el proyecto para producción

### A) **Crear base de datos en Render**

1. Ve a Render Dashboard
2. Click en "New +" → "PostgreSQL" (gratis)
3. Copia la **Internal Database URL**

### B) **Configurar variables de entorno en Render**

En tu servicio web de Render, añade estas variables:
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:TU_CLAVE_AQUI
APP_URL=https://tu-app.onrender.com

DB_CONNECTION=pgsql
DB_HOST=dpg-xxxxx.oregon-postgres.render.com
DB_PORT=5432
DB_DATABASE=tu_db_name
DB_USERNAME=tu_user
DB_PASSWORD=tu_password

JWT_SECRET=TU_JWT_SECRET_AQUI
JWT_TTL=60
JWT_REFRESH_TTL=20160

SESSION_DRIVER=file