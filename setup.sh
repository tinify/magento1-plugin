#!/bin/bash
set -e
set -x

if [ -z $MAGENTO_DB_HOST ]; then MAGENTO_DB_HOST="localhost"; fi
if [ -z $MAGENTO_DB_PORT ]; then MAGENTO_DB_PORT="3306"; fi
if [ -z $MAGENTO_DB_USER ]; then MAGENTO_DB_USER="root"; fi
if [ -z $MAGENTO_DB_PASS ]; then MAGENTO_DB_PASS=""; fi
if [ -z $MAGENTO_DB_NAME ]; then MAGENTO_DB_NAME="magento"; fi
if [ -z $MAGENTO_DB_ALLOWSAME ]; then MAGENTO_DB_ALLOWSAME="0"; fi

CURRENT_DIR=`pwd`
BUILDENV=`mktemp -d /tmp/magento.XXXXXXXX`
TOOLS="${CURRENT_DIR}/tools"
PUBLIC_DIR="${BUILDENV}/public/"

mkdir -p "${TOOLS}"
mkdir -p "${PUBLIC_DIR}"

if [ ! -f "${TOOLS}/n98-magerun" ]; then
    wget https://files.magerun.net/n98-magerun.phar -O "${TOOLS}/n98-magerun"
    chmod +x "${TOOLS}/n98-magerun"
fi

if [ ! -f "${TOOLS}/modman" ]; then
    wget https://raw.githubusercontent.com/colinmollenhour/modman/master/modman -O "${TOOLS}/modman"
    chmod +x "${TOOLS}/modman"
fi

echo "Using build directory ${BUILDENV}"

echo "Installing Magento version ${MAGENTO_VERSION}"

# Create main database
MYSQLPASS=""
if [ ! -z $MAGENTO_DB_PASS ]; then MYSQLPASS="-p${MAGENTO_DB_PASS}"; fi
mysql -u${MAGENTO_DB_USER} ${MYSQLPASS} -h${MAGENTO_DB_HOST} -P${MAGENTO_DB_PORT} -e "DROP DATABASE IF EXISTS \`${MAGENTO_DB_NAME}\`; CREATE DATABASE \`${MAGENTO_DB_NAME}\`;"

"${TOOLS}/n98-magerun" install \
      --dbHost="${MAGENTO_DB_HOST}" --dbUser="${MAGENTO_DB_USER}" --dbPass="${MAGENTO_DB_PASS}" --dbName="${MAGENTO_DB_NAME}" --dbPort="${MAGENTO_DB_PORT}" \
      --installSampleData=no \
      --useDefaultConfigParams=yes \
      --magentoVersionByName="${MAGENTO_VERSION}" \
      --installationFolder="${PUBLIC_DIR}" \
      --baseUrl="http://magento.local/" || { echo "Installing Magento failed"; exit 1; }

mkdir -p "${PUBLIC_DIR}/.modman/project"

cp -rf . "${PUBLIC_DIR}/.modman/project"

cd "${PUBLIC_DIR}"

"${TOOLS}/modman" deploy-all
"${TOOLS}/n98-magerun" config:set dev/template/allow_symlink 1
"${TOOLS}/n98-magerun" sys:setup:run

cd "${PUBLIC_DIR}/.modman/project";

phpunit

#echo "Deleting ${BUILDENV}"
#rm -rf "${BUILDENV}"