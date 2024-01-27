CREATE OR REPLACE FUNCTION describe() RETURNS trigger
AS $$
DECLARE
       rec record;
       str text := '';
BEGIN
    IF TG_LEVEL = 'ROW' THEN
       CASE TG_OP
        WHEN 'DELETE' THEN rec := OLD; str := OLD::text;
        WHEN 'UPDATE' THEN rec := NEW; str := OLD || ' -> ' || NEW;
        WHEN 'INSERT' THEN rec := NEW; str := NEW::text;
       END CASE;
    END IF;
    RAISE NOTICE '% % % %: %',
          TG_TABLE_NAME, TG_WHEN, TG_OP, TG_LEVEL, str;
    RETURN rec;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER t_before_stmt
    BEFORE INSERT OR UPDATE OR DELETE
ON t
FOR EACH STATEMENT
EXECUTE FUNCTION describe();

CREATE TRIGGER t_after_stmt
    AFTER INSERT OR UPDATE OR DELETE
        ON t
        FOR EACH STATEMENT
        EXECUTE FUNCTION describe();

CREATE TRIGGER t_before_row
    AFTER INSERT OR UPDATE OR DELETE
                    ON t
                        FOR EACH ROW
                        EXECUTE FUNCTION describe();

CREATE TRIGGER t_after_row
    AFTER INSERT OR UPDATE OR DELETE
                    ON t
                        FOR EACH ROW
                        EXECUTE FUNCTION describe();

INSERT INTO t VALUES (1, 'AAA');

INSERT INTO t VALUES (1, 'BBB'), (5,'CCC') ON CONFLICT(id) DO UPDATE SET s = EXCLUDED.s;

CREATE OR REPLACE FUNCTION transition() RETURNS trigger
AS $$
DECLARE
    rec record;
BEGIN
 IF TG_OP = 'DELETE' OR TG_OP = 'UPDATE' THEN
    RAISE NOTICE 'Старое состояние;';
    FOR rec IN SELECT * FROM old_table LOOP
        RAISE NOTICE '%', rec;
    END LOOP;
 END IF;
 IF TG_OP = 'UPDATE' OR TG_OP = 'INSERT' THEN
    RAISE NOTICE 'Новое состояние;';
    FOR rec IN SELECT * FROM new_table LOOP
        RAISE NOTICE '%', rec;
    END LOOP;
 END IF;
 RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER apt_after_upd_trans
AFTER UPDATE ON trans
REFERENCING
OLD TABLE AS old_table
NEW TABLE AS new_table
FOR EACH STATEMENT
EXECUTE FUNCTION transition();

CREATE TABLE coins(face_value NUMERIC, name TEXT);

CREATE TABLE coins_history(LIKE coins);

ALTER TABLE coins_history ADD start_date TIMESTAMP, ADD end_date TIMESTAMP;

CREATE OR REPLACE FUNCTION history_insrt() RETURNS trigger
AS $$
BEGIN
    EXECUTE format(
            'INSERT INTO %I SELECT ($1).*, current_timestamp, NULL',
            TG_TABLE_NAME|| '_history'
            )
            USING NEW;
        RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION history_delete() RETURNS trigger
AS $$
BEGIN
    EXECUTE format(
            'UPDATE %I SET end_date = current_timestamp WHERE face_value = $1 AND end_date IS NULL',
            TG_TABLE_NAME|| '_history'
            )
        USING OLD.face_value;
        RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER coins_history_insert
    AFTER INSERT OR UPDATE ON coins
FOR EACH ROW EXECUTE FUNCTION history_insrt();

CREATE TRIGGER coins_history_delete
    AFTER UPDATE OR DELETE ON coins
FOR EACH ROW EXECUTE FUNCTION history_delete();

INSERT INTO coins VALUES (0.25, 'ALUSCHKA'), (3,'ALTIN');
INSERT INTO coins VALUES (5, 'PENNIES');

DELETE FROM coins WHERE face_value = 0.25;

UPDATE coins SET name = '3 pennies' WHERE face_value = 3;

CREATE TABLE airports(
    code CHAR(3) PRIMARY KEY,
    name TEXT NOT NULL
);

INSERT INTO airports VALUES ('SVO', 'Москва, Шереметьво'),
                             ('DME', 'Москва, Домодедово'),
                             ('TOF', 'Томск');
CREATE TABLE flights(
  id INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    airport_from CHAR(3) NOT NULL REFERENCES airports(code),
    airport_to CHAR(3) NOT NULL REFERENCES airports(code),
    UNIQUE (airport_from, airport_to)
);

INSERT INTO flights(airport_from, airport_to) VALUES ('SVO', 'TOF');

CREATE VIEW flights_v AS
    SELECT id,
        (SELECT name FROM airports WHERE code = airport_from) airport_from,
        (SELECT name FROM airports WHERE code = airport_to) airport_to FROM flights;

UPDATE flights_v SET airport_to = 'Грозный. Сахалин' WHERE id = 1;

CREATE OR REPLACE FUNCTION flights_v_update() RETURNS trigger
AS $$
DECLARE
    code_to CHAR(3);
BEGIN
    BEGIN
    SELECT code INTO STRICT code_to
    FROM airports
    WHERE name = NEW.airport_to;
    EXCEPTION
        WHEN no_data_found THEN
            RAISE EXCEPTION 'Not found % airport', NEW.airport_to;
    END;
    UPDATE flights SET airport_to = code_to
    WHERE id = OLD.id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_flights_upd_trigger
INSTEAD OF UPDATE ON flights_v
FOR EACH ROW EXECUTE FUNCTION flights_v_update();

CREATE VIEW account_v AS
    SELECT '(0,'||lp||')' AS ctid,
           t_xmax AS xmax,
           CASE WHEN (t_infomask & 1024) > 0 THEN 't' END AS comitted,
           CASE WHEN (t_infomask & 2048) > 0 THEN 't' END AS aborted,
           CASE WHEN (t_infomask & 128) > 0 THEN 't' END AS lock_only,
           CASE WHEN (t_infomask & 4096) > 0 THEN 't' END AS is_multi,
           CASE WHEN (t_infomask2 & 8192) > 0 THEN 't' END AS keys_upd
    FROM heap_page_items(get_raw_page('account',0))
    WHERE lp <= 3
    ORDER BY lp;

UPDATE account SET amount = amount + 100 WHERE acc_no = 1;
UPDATE account SET acc_no = 20 WHERE acc_no = 2;

SELECT * FROM account WHERE acc_no = 1 FOR KEY SHARE;
SELECT * FROM account WHERE acc_no = 1 FOR SHARE;

SELECT * FROM accounts_v;

CREATE EXTENSION pgrowlocks;

SELECT * FROM locks WHERE pid = 281;

SELECT * FROM pgrowlocks('account');

pg_try_advisory*

CREATE VIEW locks AS
    SELECT pid, locktype,
           CASE locktype
            WHEN 'relation' THEN relation::regclass::text
            WHEN 'virtualxid' THEN virtualxid::text
            WHEN 'transactionid' THEN transactionid::text
            WHEN 'tuple' THEN relation::REGCLASS::text||':'||page::text||','||tuple::text
            END AS lockid,
        mode,
        granted
    FROM pg_locks;

SELECT * FROM txid_current(), pg_backend_pid();

SELECT * FROM account WHERE acc_no = 1 FOR SHARE;

SELECT * FROM pg_blocking_pids(389);

SELECT pg_index.indisvalid FROM pg_class, pg_index WHERE pg_index.indexrelid = pg_class.oid AND pg_class.relname = 'btree_payment_player_idx';

ALTER TABLE payment ADD CONSTRAINT fk_account
FOREIGN KEY (account_id) REFERENCES account(id) NOT VALID
ALTER TABLE payment VALIDATE CONSTRAINT fk_account_id




