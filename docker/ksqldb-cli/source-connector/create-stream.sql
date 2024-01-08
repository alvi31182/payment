--RUN SCRIPT /etc/sql/source_connectors.sql;

CREATE STREAM payment_views(
       id VARCHAR,
       player_id VARCHAR,
       amount VARCHAR,
       amount_type VARCHAR,
       currency VARCHAR
) WITH (
       KAFKA_TOPIC = 'kafka_v1_payment',
       VALUE_FORMAT = 'JSON',
       PARTITIONS=1,
       REPLICAS=1
);