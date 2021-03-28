#!/usr/bin/env bash

set -e

SCRIPT_PATH=$(dirname "$(realpath "$0")")

# Create a root CA signing key.
openssl genrsa -out "${SCRIPT_PATH}/openldapCA.key" 4096

# Now create and self-sign the root CA certificate.
openssl req -x509 -new -nodes -key "${SCRIPT_PATH}/openldapCA.key" -sha256 -days 3650 -subj "/CN=localhostCA" -out "${SCRIPT_PATH}/openldapCA.crt"

# Generate the LDAP server key.
openssl genrsa -out "${SCRIPT_PATH}/openldap.key" 2048

# Now create the CSR for the LDAP server certificate so we can sign it with our root CA.
openssl req -new -sha256 -key "${SCRIPT_PATH}/openldap.key" -subj "/CN=localhost" -out "${SCRIPT_PATH}/openldap.csr"

# Finally, sign the LDAP server CSR with our root CA so it's ready to use.
openssl x509 -req -in "${SCRIPT_PATH}/openldap.csr" -CA "${SCRIPT_PATH}/openldapCA.crt" -CAkey "${SCRIPT_PATH}/openldapCA.key" -CAcreateserial -out "${SCRIPT_PATH}/openldap.crt" -sha256 -days 3650

# Remove the CSR as it's no longer needed.
rm "${SCRIPT_PATH}/openldap.csr"

exit 0
