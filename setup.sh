#!/bin/bash
set -e
set -x

TMPNAME=`openssl rand -base64 32 | tr -cd '[:alnum:]' | head -c8`;

if [ -z $MAGENTO_DB_HOST ]; then MAGENTO_DB_HOST="localhost"; fi
if [ -z $MAGENTO_DB_PORT ]; then MAGENTO_DB_PORT="3306"; fi
if [ -z $MAGENTO_DB_USER ]; then MAGENTO_DB_USER="root"; fi
if [ -z $MAGENTO_DB_PASS ]; then MAGENTO_DB_PASS=""; fi
if [ -z $MAGENTO_DB_ALLOWSAME ]; then MAGENTO_DB_ALLOWSAME="0"; fi
if [ -z $MAGENTO_DB_NAME ]; then
    MAGENTO_DB_NAME="magento_${TMPNAME}";
fi

CURRENT_DIR=`pwd`
BUILDENV="/tmp/magento.${TMPNAME}"
TOOLS="${CURRENT_DIR}/tools"
PUBLIC_DIR="${BUILDENV}/public/"

mkdir -p "${TOOLS}"
mkdir -p "${PUBLIC_DIR}"

composer global require n98/magerun colinmollenhour/modman

echo "Using build directory ${BUILDENV}"

echo "Installing Magento version ${MAGENTO_VERSION}"

# Create main database
MYSQLPASS=""
if [ ! -z $MAGENTO_DB_PASS ]; then MYSQLPASS="-p${MAGENTO_DB_PASS}"; fi
mysql -u${MAGENTO_DB_USER} ${MYSQLPASS} -h${MAGENTO_DB_HOST} -P${MAGENTO_DB_PORT} -e "DROP DATABASE IF EXISTS \`${MAGENTO_DB_NAME}\`; CREATE DATABASE \`${MAGENTO_DB_NAME}\`;"

n98-magerun install \
      --dbHost="${MAGENTO_DB_HOST}" --dbUser="${MAGENTO_DB_USER}" --dbPass="${MAGENTO_DB_PASS}" --dbName="${MAGENTO_DB_NAME}" --dbPort="${MAGENTO_DB_PORT}" \
      --installSampleData=no \
      --useDefaultConfigParams=yes \
      --magentoVersionByName="${MAGENTO_VERSION}" \
      --installationFolder="${PUBLIC_DIR}" \
      --baseUrl="http://magento.local/" || { echo "Installing Magento failed"; exit 1; }

mkdir -p "${PUBLIC_DIR}/.modman/project"

cp -rf . "${PUBLIC_DIR}/.modman/project"

cd "${PUBLIC_DIR}"

modman deploy-all
n98-magerun config:set dev/template/allow_symlink 1
n98-magerun sys:setup:run

cd "${PUBLIC_DIR}/.modman/project";

phpunit

mysql -u${MAGENTO_DB_USER} ${MYSQLPASS} -h${MAGENTO_DB_HOST} -P${MAGENTO_DB_PORT} -e "DROP DATABASE IF EXISTS \`${MAGENTO_DB_NAME}\`;"
echo "Deleting ${BUILDENV}"
rm -rf "${BUILDENV}"