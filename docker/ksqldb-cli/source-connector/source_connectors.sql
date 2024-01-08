CREATE SOURCE CONNECTOR `postgres-source` WITH(
    "connector.class"='io.confluent.connect.jdbc.JdbcSourceConnector',
    "connection.url"='jdbc:postgresql://db:5432/payment?user=user&password=pass',
    "mode"='timestamp',
    "timestamp.column.name"='created_at',
    "topic.prefix"='kafka_v1_',
    "table.whitelist"='payment',
    "key"='id');