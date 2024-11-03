set -e

user=$MONGO_INITDB_USER
password=$MONGO_INITDB_PWD
db=$MONGO_INITDB_DATABASE

mongosh -u $MONGO_INITDB_ROOT_USERNAME -p $MONGO_INITDB_ROOT_PASSWORD <<EOF
use ${db}
db = db.getSiblingDB("${db}")
db.createUser({
  user: "${user}",
  pwd: "${password}",
  roles: [{ role: "readWrite", db: "${db}"}],
})
EOF