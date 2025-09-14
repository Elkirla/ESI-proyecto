#!/bin/sh

# Configuración
BACKUP_DIR="/backups"
DATE=$(date +%Y%m%d_%H%M%S)
DB_HOST="db"              # Nombre del servicio en docker-compose
DB_NAME="cooperativa"
DB_USER="usuariodb"
DB_PASS="password"

# Crear directorio de respaldo si no existe
mkdir -p ${BACKUP_DIR}

# Log inicio
echo "[$(date)] Iniciando respaldo de base de datos..." >> ${BACKUP_DIR}/backup.log

# Generar backup
mysqldump -h${DB_HOST} -u${DB_USER} -p${DB_PASS} ${DB_NAME} > ${BACKUP_DIR}/backup_${DATE}.sql

# Comprimir
gzip ${BACKUP_DIR}/backup_${DATE}.sql

# Limpiar respaldos antiguos 
find ${BACKUP_DIR} -name "*.gz" -type f -mtime +7 -delete

echo "[$(date)] Respaldo completado: backup_${DATE}.sql.gz" >> ${BACKUP_DIR}/backup.log
