DO $$
    DECLARE
        cur refcursor;
    BEGIN
        OPEN cur FOR SELECT * FROM account;
END;
$$;

DO $$
    DECLARE
        cur refcursor;
        rec record;
    BEGIN
        OPEN cur FOR SELECT * FROM account ORDER BY id;
        MOVE cur;
        FETCH cur INTO rec;
        RAISE NOTICE '%', rec;
        CLOSE cur;
    END;
    $$;

DO $$
    DECLARE
        cur CURSOR FOR SELECT * FROM account;
    BEGIN
        FOR rec IN cur LOOP
            RAISE NOTICE '%', rec;
        END LOOP;
    END;
    $$;

DO $$
    DECLARE
        cur refcursor;
        rec record;
    BEGIN
        OPEN cur FOR SELECT FROM account
            FOR UPDATE;
        LOOP
            FETCH cur INTO rec;
            EXIT WHEN NOT FOUND;
            UPDATE account SET amount = amount || '(UPDATED)' WHERE CURRENT OF cur;
        END LOOP;
        CLOSE cur;
    END;
    $$;
