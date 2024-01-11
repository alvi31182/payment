CREATE STREAM payment_deposit WITH (
       KAFKA_TOPIC = 'payment_deposit',
       VALUE_FORMAT = 'JSON'
       )
       AS SELECT player_id, amount, amount_type FROM `PAYMENT_VIEWS` WHERE amount_type = 'deposit' EMIT CHANGES;