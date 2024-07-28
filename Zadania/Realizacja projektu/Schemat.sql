--
-- PostgreSQL database dump
--

-- Dumped from database version 16.1
-- Dumped by pg_dump version 16.1

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: aktualizuj_dane_pojazdu(integer, character varying, character varying, character varying, character varying, integer, integer, integer, integer, character varying, integer, integer, numeric); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.aktualizuj_dane_pojazdu(id_pojazdu_param integer, vin_param character varying, marka_param character varying, model_param character varying, nadwozie_param character varying, rok_produkcji_param integer, przebieg_param integer, pojemnosc_silnika_param integer, moc_silnika_param integer, rodzaj_paliwa_param character varying, liczba_drzwi_param integer, liczba_miejsc_param integer, cena_param numeric) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE

    -- Deklaracja zmiennych lokalnych
    id_cechy_ret INT;
	
-- Rozpoczęcie transakcji
BEGIN

    -- Sprawdzenie czy cechy pojazdu już istnieją
    SELECT id_cechy_pojazdu INTO id_cechy_ret FROM cechy_pojazdu 
    WHERE marka = marka_param AND model = model_param AND nadwozie = nadwozie_param;

    -- Jeśli cechy pojazdu nie istnieją, to tworzone są nowe cechy pojazdu
    IF id_cechy_ret IS NULL THEN
        INSERT INTO cechy_pojazdu (marka, model, nadwozie)
        VALUES (marka_param, model_param, nadwozie_param)
        RETURNING id_cechy_pojazdu INTO id_cechy_ret;
    END IF;

    -- Aktualizacja danych pojazdu
    UPDATE pojazdy SET
        vin = vin_param,
        id_cechy_pojazdu = id_cechy_ret,
        rok_produkcji = rok_produkcji_param,
        przebieg = przebieg_param,
        pojemnosc_silnika = pojemnosc_silnika_param,
        moc_silnika = moc_silnika_param,
        rodzaj_paliwa = rodzaj_paliwa_param,
        liczba_drzwi = liczba_drzwi_param,
        liczba_miejsc = liczba_miejsc_param,
        cena = cena_param
    WHERE id_pojazdu = id_pojazdu_param;

    -- Zakończenie transakcji
    RETURN 'Pojazd zaktualizowany pomyślnie.';
EXCEPTION
    WHEN OTHERS THEN
	
	    -- Obsługa błędów
        RETURN 'Przepraszamy, wystąpił problem z edycją pojazdu, spróbuj ponownie później.';
END;
$$;


ALTER FUNCTION public.aktualizuj_dane_pojazdu(id_pojazdu_param integer, vin_param character varying, marka_param character varying, model_param character varying, nadwozie_param character varying, rok_produkcji_param integer, przebieg_param integer, pojemnosc_silnika_param integer, moc_silnika_param integer, rodzaj_paliwa_param character varying, liczba_drzwi_param integer, liczba_miejsc_param integer, cena_param numeric) OWNER TO postgres;

--
-- Name: aktualizuj_dane_uzytkownika(integer, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.aktualizuj_dane_uzytkownika(id_konta_param integer, imie_param character varying, nazwisko_param character varying, numer_telefonu_param character varying, ulica_param character varying, numer_domu_param character varying, kod_pocztowy_param character varying, miejscowosc_param character varying, kraj_param character varying, nowe_haslo_param character varying, zmiana_hasla_param boolean) RETURNS text
    LANGUAGE plpgsql
    AS $$
BEGIN

    -- Rozpoczęcie transakcji
    BEGIN
	
        -- Aktualizacja danych użytkownika
        UPDATE dane SET
            imie = imie_param,
            nazwisko = nazwisko_param,
            numer_telefonu = numer_telefonu_param,
            ulica = ulica_param,
            numer_domu = numer_domu_param,
            kod_pocztowy = kod_pocztowy_param,
            miejscowosc = miejscowosc_param,
            kraj = kraj_param
        WHERE id_danych = (SELECT id_danych FROM konta WHERE id_konta = id_konta_param);

        -- Opcjonalna zmiana hasła
        IF zmiana_hasla_param THEN
            UPDATE konta SET
                haslo = nowe_haslo_param
            WHERE id_konta = id_konta_param;
        END IF;

        -- Zakończenie transakcji
        RETURN 'Dane zaktualizowano pomyślnie.';
    EXCEPTION
        WHEN OTHERS THEN
		
		    -- Obsługa błędów
            RETURN 'Przepraszamy, wystąpił problem z edycją, spróbuj ponownie później.';
    END;
END;
$$;


ALTER FUNCTION public.aktualizuj_dane_uzytkownika(id_konta_param integer, imie_param character varying, nazwisko_param character varying, numer_telefonu_param character varying, ulica_param character varying, numer_domu_param character varying, kod_pocztowy_param character varying, miejscowosc_param character varying, kraj_param character varying, nowe_haslo_param character varying, zmiana_hasla_param boolean) OWNER TO postgres;

--
-- Name: dodaj_nowy_pojazd(character varying, character varying, character varying, character varying, integer, integer, integer, integer, character varying, integer, integer, numeric, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.dodaj_nowy_pojazd(vin_param character varying, marka_param character varying, model_param character varying, nadwozie_param character varying, rok_produkcji_param integer, przebieg_param integer, pojemnosc_silnika_param integer, moc_silnika_param integer, rodzaj_paliwa_param character varying, liczba_drzwi_param integer, liczba_miejsc_param integer, cena_param numeric, id_klienta_param integer) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE

    -- Deklaracja zmiennych lokalnych
    id_cechy_ret INT;
	
-- Rozpoczęcie transakcji
BEGIN

    -- Sprawdzenie czy cechy pojazdu już istnieją
    SELECT id_cechy_pojazdu INTO id_cechy_ret FROM cechy_pojazdu 
    WHERE marka = marka_param AND model = model_param AND nadwozie = nadwozie_param;

    -- Jeśli cechy pojazdu nie istnieją, to tworzone są nowe cechy pojazdu
    IF id_cechy_ret IS NULL THEN
        INSERT INTO cechy_pojazdu (marka, model, nadwozie)
        VALUES (marka_param, model_param, nadwozie_param)
        RETURNING id_cechy_pojazdu INTO id_cechy_ret;
    END IF;

    -- Tworzenie nowego pojazdu
    INSERT INTO pojazdy 
	(
        vin, id_cechy_pojazdu, rok_produkcji, przebieg, 
		pojemnosc_silnika, moc_silnika, rodzaj_paliwa, 
		liczba_drzwi, liczba_miejsc, cena, 
        id_wlasciciela, status_pojazdu
    ) 
	VALUES 
	(
        vin_param, id_cechy_ret, rok_produkcji_param, przebieg_param, 
		pojemnosc_silnika_param, moc_silnika_param, rodzaj_paliwa_param, 
		liczba_drzwi_param, liczba_miejsc_param, cena_param, 
        id_klienta_param, 'W bazie'
    );

    -- Zakończenie transakcji
    RETURN 'Pojazd dodany pomyślnie.';
EXCEPTION
    WHEN OTHERS THEN
	
	    -- Obsługa błędów
        RETURN 'Przepraszamy, wystąpił problem z dodawaniem pojazdu, spróbuj ponownie później.';
END;
$$;


ALTER FUNCTION public.dodaj_nowy_pojazd(vin_param character varying, marka_param character varying, model_param character varying, nadwozie_param character varying, rok_produkcji_param integer, przebieg_param integer, pojemnosc_silnika_param integer, moc_silnika_param integer, rodzaj_paliwa_param character varying, liczba_drzwi_param integer, liczba_miejsc_param integer, cena_param numeric, id_klienta_param integer) OWNER TO postgres;

--
-- Name: dodaj_zdjecie_pojazdu(integer, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.dodaj_zdjecie_pojazdu(id_pojazdu_param integer, nazwa_zdjecia_param character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN

    -- Rozpoczęcie transakcji
    BEGIN
	
	    -- Dodanie zdjęcia pojazdu
        INSERT INTO zdjecia_pojazdow (id_pojazdu, nazwa_zdjecia)
        VALUES (id_pojazdu_param, nazwa_zdjecia_param);
		
		-- Zakończenie transakcji
    EXCEPTION
        WHEN OTHERS THEN
		
            -- Obsługa błędów
            RAISE EXCEPTION 'Błąd podczas dodawania zdjęcia pojazdu';
    END;
END;
$$;


ALTER FUNCTION public.dodaj_zdjecie_pojazdu(id_pojazdu_param integer, nazwa_zdjecia_param character varying) OWNER TO postgres;

--
-- Name: pobierz_dane_klienta(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.pobierz_dane_klienta(id_konta_param integer) RETURNS TABLE(klient_data json)
    LANGUAGE plpgsql
    AS $$
BEGIN

    -- Rozpoczęcie transakcji
    BEGIN
        RETURN QUERY
		
        -- Pobranie danych klienta i przekształcenie na format JSON
        SELECT
            json_build_object
			(
                'id_klienta', k.id_klienta,
                'id_konta', k.id_konta,
                'stan_konta', k.stan_konta
            ) 
			AS klient_data
        FROM klienci k
        WHERE k.id_konta = id_konta_param;
		
	-- Zakończenie transakcji
    EXCEPTION
        WHEN OTHERS THEN
		
            -- Obsługa błędów
            RAISE EXCEPTION 'Wystąpił błąd podczas pobierania danych klienta';
    END;
END;
$$;


ALTER FUNCTION public.pobierz_dane_klienta(id_konta_param integer) OWNER TO postgres;

--
-- Name: pobierz_dane_pojazdu_plus_cechy_zdjecia(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.pobierz_dane_pojazdu_plus_cechy_zdjecia(id_pojazdu_param integer) RETURNS TABLE(pojazd_data json, zdjecia_pojazdu_data json)
    LANGUAGE plpgsql
    AS $$
BEGIN

    -- Rozpoczęcie transakcji
    BEGIN
        RETURN QUERY
        SELECT
		
            -- Tworzenie obiektu JSON z danymi pojazdu
            json_build_object
            (
                'id_pojazdu', p.id_pojazdu,
                'vin', p.vin,
                'id_cechy_pojazdu', p.id_cechy_pojazdu,
                'rok_produkcji', p.rok_produkcji,
                'przebieg', p.przebieg,
                'pojemnosc_silnika', p.pojemnosc_silnika,
                'moc_silnika', p.moc_silnika,
                'rodzaj_paliwa', p.rodzaj_paliwa,
                'liczba_drzwi', p.liczba_drzwi,
                'liczba_miejsc', p.liczba_miejsc,
                'cena', p.cena,
                'id_wlasciciela', p.id_wlasciciela,
                'status_pojazdu', p.status_pojazdu,
                'marka', c.marka,
                'model', c.model,
                'nadwozie', c.nadwozie
            ) AS pojazd_data,
            
            -- Tworzenie tablicy JSON ze zdjęciami pojazdu
            (
                SELECT json_agg
                (
                    json_build_object
                    (
                        'id_zdjecia', z.id_zdjecia,
						'id_pojazdu', z.id_pojazdu,
                        'nazwa_zdjecia', z.nazwa_zdjecia
                    )
                )
                FROM zdjecia_pojazdow z
                WHERE z.id_pojazdu = p.id_pojazdu
            ) AS zdjecia_pojazdu_data
            
        -- Łączenie tabel 'pojazdy' i 'cechy_pojazdu'
        FROM pojazdy p
        JOIN cechy_pojazdu c ON c.id_cechy_pojazdu = p.id_cechy_pojazdu
        WHERE p.id_pojazdu = id_pojazdu_param;
		
		-- Zakończenie transakcji
    EXCEPTION
        WHEN OTHERS THEN
		
            -- Obsługa błędów
            RETURN QUERY 
            SELECT 
                json_build_object('error', 'Wystąpił nieoczekiwany błąd podczas pobierania danych pojazdu') AS pojazd_data,
                NULL::JSON AS zdjecia_pojazdu_data;
    END;
END;
$$;


ALTER FUNCTION public.pobierz_dane_pojazdu_plus_cechy_zdjecia(id_pojazdu_param integer) OWNER TO postgres;

--
-- Name: pobierz_dane_pracownika(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.pobierz_dane_pracownika(id_konta_param integer) RETURNS TABLE(id_pracownika integer, stanowisko character varying, czy_pracownik boolean)
    LANGUAGE plpgsql
    AS $$

-- Rozpoczęcie transakcji
BEGIN

    -- Zwracanie zapytania pobierającego dane pracownika
    RETURN QUERY
    SELECT
        p.id_pracownika::INT,  -- id_pracownika = INT (pobranie i konwersja id_pracownika)
        p.stanowisko,          -- stanowisko = VARCHAR (pobranie stanowiska pracownika)
        TRUE AS czy_pracownik  -- czy_pracownik = TRUE
    FROM pracownicy p
    WHERE p.id_konta = id_konta_param;

    -- Sprawdzenie, czy nie znaleziono żadnego pracownika
    IF NOT FOUND THEN
	
        -- Zwrócenie wartości domyślnych, jeśli nie znaleziono żadnego pracownika
        RETURN QUERY 
        SELECT 
            NULL::INT AS id_pracownika,  -- id_pracownika = NULL
            NULL::VARCHAR AS stanowisko, -- stanowisko = NULL
            FALSE AS czy_pracownik;      -- czy_pracownik = FALSE
    END IF;
	
	-- Zakończenie transakcji
EXCEPTION
    WHEN OTHERS THEN
	
        -- Obsługa błędów
        RETURN QUERY 
        SELECT 
            NULL::INT AS id_pracownika,  -- id_pracownika = NULL
            NULL::VARCHAR AS stanowisko, -- stanowisko = NULL
            FALSE AS czy_pracownik;      -- czy_pracownik = FALSE
END;
$$;


ALTER FUNCTION public.pobierz_dane_pracownika(id_konta_param integer) OWNER TO postgres;

--
-- Name: pobierz_pojazdy_plus_cechy_zdjecia(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.pobierz_pojazdy_plus_cechy_zdjecia(id_wlasciciela_param integer) RETURNS TABLE(pojazdy_data json)
    LANGUAGE plpgsql
    AS $$
BEGIN

    -- Rozpoczęcie transakcji
    BEGIN
        RETURN QUERY
		
		-- Tworzenie tablicy JSON z pojazdami
		SELECT json_agg
		(
			
			-- Tworzenie obiektu JSON z danymi pojazdu
            json_build_object
			(
                'id_pojazdu', p.id_pojazdu,
                'vin', p.vin,
                'id_cechy_pojazdu', p.id_cechy_pojazdu,
                'rok_produkcji', p.rok_produkcji,
                'przebieg', p.przebieg,
                'pojemnosc_silnika', p.pojemnosc_silnika,
                'moc_silnika', p.moc_silnika,
                'rodzaj_paliwa', p.rodzaj_paliwa,
                'liczba_drzwi', p.liczba_drzwi,
                'liczba_miejsc', p.liczba_miejsc,
                'cena', p.cena,
                'id_wlasciciela', p.id_wlasciciela,
                'status_pojazdu', p.status_pojazdu,
                'marka', c.marka,
                'model', c.model,
                'nadwozie', c.nadwozie,
                'zdjecia', 
				(
					
					-- Tworzenie tablicy JSON ze zdjęciami pojazdu
                    SELECT json_agg
					(
                        json_build_object
						(
                            'id_zdjecia', z.id_zdjecia,
                            'id_pojazdu', z.id_pojazdu,
                            'nazwa_zdjecia', z.nazwa_zdjecia
                        )
                    )
                    FROM zdjecia_pojazdow z
                    WHERE z.id_pojazdu = p.id_pojazdu
                )
            )
        ) AS pojazdy_data
		
		-- Łączenie tabel 'pojazdy' i 'cechy_pojazdu'
        FROM pojazdy p
        JOIN cechy_pojazdu c ON c.id_cechy_pojazdu = p.id_cechy_pojazdu
        WHERE p.id_wlasciciela = id_wlasciciela_param;
		
	-- Zakończenie transakcji	
    EXCEPTION
        WHEN OTHERS THEN
		
            -- Obsługa błędów
            RETURN QUERY 
            SELECT 
                json_build_object('error', 'Wystąpił nieoczekiwany błąd podczas pobierania danych pojazdów') AS pojazdy_data;
    END;
END;
$$;


ALTER FUNCTION public.pobierz_pojazdy_plus_cechy_zdjecia(id_wlasciciela_param integer) OWNER TO postgres;

--
-- Name: pobierz_pojazdy_plus_cechy_zdjecia_w_serwisie(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.pobierz_pojazdy_plus_cechy_zdjecia_w_serwisie() RETURNS TABLE(pojazd_data json, zdjecia_pojazdu_data json)
    LANGUAGE plpgsql
    AS $$
BEGIN

    -- Rozpoczęcie transakcji
    BEGIN
        RETURN QUERY
        SELECT
		
            -- Tworzenie obiektu JSON z danymi pojazdu
            json_build_object
            (
                'id_pojazdu', p.id_pojazdu,
                'vin', p.vin,
                'id_cechy_pojazdu', p.id_cechy_pojazdu,
                'rok_produkcji', p.rok_produkcji,
                'przebieg', p.przebieg,
                'pojemnosc_silnika', p.pojemnosc_silnika,
                'moc_silnika', p.moc_silnika,
                'rodzaj_paliwa', p.rodzaj_paliwa,
                'liczba_drzwi', p.liczba_drzwi,
                'liczba_miejsc', p.liczba_miejsc,
                'cena', p.cena,
                'id_wlasciciela', p.id_wlasciciela,
                'status_pojazdu', p.status_pojazdu,
                'marka', c.marka,
                'model', c.model,
                'nadwozie', c.nadwozie
            ) 
            AS pojazd_data,
            
            -- Tworzenie tablicy JSON ze zdjęciami pojazdu
            (
                SELECT json_agg(json_build_object('nazwa_zdjecia', z.nazwa_zdjecia))
                FROM zdjecia_pojazdow z
                WHERE z.id_pojazdu = p.id_pojazdu
            ) 
            AS zdjecia_pojazdu_data
            
        -- Łączenie tabel 'pojazdy' i 'cechy_pojazdu'
        FROM pojazdy p
        JOIN cechy_pojazdu c ON c.id_cechy_pojazdu = p.id_cechy_pojazdu
        
        -- Warunek pobierający pojazdy z odpowiednim statusem
        WHERE p.status_pojazdu = 'W serwisie';
		
		-- Zakończenie transakcji
    EXCEPTION
        WHEN OTHERS THEN
		
            -- Obsługa błędów
            RETURN QUERY 
            SELECT 
                json_build_object('error', 'Wystąpił nieoczekiwany błąd podczas pobierania danych pojazdów') AS pojazd_data,
                NULL::JSON AS zdjecia_pojazdu_data;
    END;
END;
$$;


ALTER FUNCTION public.pobierz_pojazdy_plus_cechy_zdjecia_w_serwisie() OWNER TO postgres;

--
-- Name: pobierz_wszystkie_pojazdy_plus_cechy_zdjecia(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.pobierz_wszystkie_pojazdy_plus_cechy_zdjecia() RETURNS TABLE(pojazd_data json, zdjecia_pojazdu_data json)
    LANGUAGE plpgsql
    AS $$
BEGIN

    -- Rozpoczęcie transakcji
    BEGIN
        RETURN QUERY
        SELECT
		
            -- Tworzenie obiektu JSON z danymi pojazdu
            json_build_object
            (
                'id_pojazdu', p.id_pojazdu,
                'vin', p.vin,
                'id_cechy_pojazdu', p.id_cechy_pojazdu,
                'rok_produkcji', p.rok_produkcji,
                'przebieg', p.przebieg,
                'pojemnosc_silnika', p.pojemnosc_silnika,
                'moc_silnika', p.moc_silnika,
                'rodzaj_paliwa', p.rodzaj_paliwa,
                'liczba_drzwi', p.liczba_drzwi,
                'liczba_miejsc', p.liczba_miejsc,
                'cena', p.cena,
                'id_wlasciciela', p.id_wlasciciela,
                'status_pojazdu', p.status_pojazdu,
                'marka', c.marka,
                'model', c.model,
                'nadwozie', c.nadwozie
            ) 
            AS pojazd_data,
            
            -- Tworzenie tablicy JSON ze zdjęciami pojazdu
            (
                SELECT json_agg(json_build_object('id_zdjecia', z.id_zdjecia, 'nazwa_zdjecia', z.nazwa_zdjecia))
                FROM zdjecia_pojazdow z
                WHERE z.id_pojazdu = p.id_pojazdu
            ) 
            AS zdjecia_pojazdu_data
            
        -- Łączenie tabel 'pojazdy' i 'cechy_pojazdu'
        FROM pojazdy p
        JOIN cechy_pojazdu c ON c.id_cechy_pojazdu = p.id_cechy_pojazdu;
		
		-- Zakończenie transakcji
    EXCEPTION
        WHEN OTHERS THEN
		
            -- Obsługa błędów
            RETURN QUERY 
            SELECT 
                json_build_object('error', 'Wystąpił nieoczekiwany błąd podczas pobierania danych pojazdów') AS pojazd_data,
                NULL::JSON AS zdjecia_pojazdu_data;
    END;
END;
$$;


ALTER FUNCTION public.pobierz_wszystkie_pojazdy_plus_cechy_zdjecia() OWNER TO postgres;

--
-- Name: sprawdz_wlasciciela_pojazdu(integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.sprawdz_wlasciciela_pojazdu(id_pojazdu_param integer, id_konta_param integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE

    -- Deklaracja zmiennych lokalnych
    typ_konta_ret VARCHAR;
    id_wlasciciela_ret INT;
	
-- Rozpoczęcie transakcji
BEGIN

    -- Pobranie typu konta
    SELECT typ_konta INTO typ_konta_ret 
    FROM konta 
    WHERE id_konta = id_konta_param;

    -- Sprawdzenie typu konta
    IF typ_konta_ret = 'pracownik' THEN
        RETURN FALSE;
    END IF;

    -- Pobranie ID właściciela pojazdu
    SELECT id_wlasciciela INTO id_wlasciciela_ret 
    FROM pojazdy 
    WHERE id_pojazdu = id_pojazdu_param;

    -- Zakończenie transakcji
	-- Zwracanie TRUE, jeśli ID właściciela pojazdu jest równe ID konta, w przeciwnym przypadku zwracanie FALSE
    RETURN (id_wlasciciela_ret = id_konta_param);
EXCEPTION
    WHEN OTHERS THEN
	
        -- Obsługa błędów
		-- Zwracanie FALSE w przypadku błędu
        RETURN FALSE;
END;
$$;


ALTER FUNCTION public.sprawdz_wlasciciela_pojazdu(id_pojazdu_param integer, id_konta_param integer) OWNER TO postgres;

--
-- Name: usun_pojazd(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.usun_pojazd(id_pojazdu_param integer) RETURNS text
    LANGUAGE plpgsql
    AS $$
BEGIN

    -- Rozpoczęcie transakcji
    BEGIN
	
        -- Usuwanie powiązanych rekordów sprzedanych pojazdów
        DELETE FROM sprzedane_pojazdy WHERE id_pojazdu = id_pojazdu_param;

        -- Usuwanie powiązanych rekordów pojazdów w serwisie
        DELETE FROM serwisowane_pojazdy WHERE id_pojazdu = id_pojazdu_param;

        -- Usuwanie powiązanych rekordów wystawionych pojazdów na sprzedaż
        DELETE FROM wystawione_pojazdy_sprzedaz WHERE id_pojazdu = id_pojazdu_param;

        -- Usuwanie powiązanych rekordów zdjęć pojazdów
        DELETE FROM zdjecia_pojazdow WHERE id_pojazdu = id_pojazdu_param;

        -- Usuwanie pojazdu
        DELETE FROM pojazdy WHERE id_pojazdu = id_pojazdu_param;
        IF NOT FOUND THEN
            RETURN 'Nie znaleziono pojazdu do usunięcia.';
        END IF;

        -- Zakończenie transakcji
        RETURN 'Pojazd został pomyślnie usunięty.';
    EXCEPTION
        WHEN OTHERS THEN
		
            -- Obsługa wyjątków
            RETURN 'Wystąpił problem, spróbuj ponownie później.';
    END;
END;
$$;


ALTER FUNCTION public.usun_pojazd(id_pojazdu_param integer) OWNER TO postgres;

--
-- Name: wyslij_do_serwisu(integer, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.wyslij_do_serwisu(id_pojazdu_param integer, opis_usterki_param text) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE

    -- Deklaracja zmiennych lokalnych
    id_pracownika_ret INT;
BEGIN

    -- Rozpoczęcie transakcji
    BEGIN
	
        -- Losowe wybranie pracownika
        SELECT id_pracownika INTO id_pracownika_ret
        FROM pracownicy
        ORDER BY RANDOM()
        LIMIT 1;

        -- Sprawdzenie, czy znaleziono pracownika
        IF id_pracownika_ret IS NULL THEN
            RETURN 'Nie znaleziono dostępnych pracowników.';
        END IF;

        -- Aktualizacja statusu pojazdu
        UPDATE pojazdy
        SET status_pojazdu = 'W serwisie'
        WHERE id_pojazdu = id_pojazdu_param;

        -- Rejestrowanie pojazdu w serwisie
        INSERT INTO serwisowane_pojazdy (id_pracownika, id_pojazdu, opis_usterki, data_poczatku_serwisu, status_serwisu, data_konca_serwisu)
        VALUES (id_pracownika_ret, id_pojazdu_param, opis_usterki_param, NOW(), 'W trakcie', NULL);

        -- Zakończenie transakcji
        RETURN 'Pojazd wysłany do serwisu pomyślnie.';
    EXCEPTION
        WHEN OTHERS THEN
		
            -- Obsługa błędów
            RETURN 'Wystąpił problem, spróbuj ponownie później.';
    END;
END;
$$;


ALTER FUNCTION public.wyslij_do_serwisu(id_pojazdu_param integer, opis_usterki_param text) OWNER TO postgres;

--
-- Name: wystaw_pojazd_na_sprzedaz(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.wystaw_pojazd_na_sprzedaz(id_pojazdu_param integer) RETURNS text
    LANGUAGE plpgsql
    AS $$
BEGIN

    -- Rozpoczęcie transakcji
    BEGIN
	
        -- Aktualizacja statusu pojazdu
        UPDATE pojazdy
        SET status_pojazdu = 'Na sprzedaż'
        WHERE id_pojazdu = id_pojazdu_param;
        
        -- Sprawdzenie, czy aktualizacja powiodła się
        IF NOT FOUND THEN
            RETURN 'Wystąpił problem, spróbuj ponownie później.';
        END IF;

        -- Wstawienie nowego wpisu w tabeli sprzedaży pojazdów
        INSERT INTO wystawione_pojazdy_sprzedaz (id_pojazdu, data_wystawienia, status_ogloszenia, data_zakonczenia)
        VALUES (id_pojazdu_param, NOW(), 'W trakcie', NULL);

        -- Zakończenie transakcji
        RETURN 'Pojazd wystawiony na sprzedaż pomyślnie.';
    EXCEPTION
        WHEN OTHERS THEN
		
            -- Obsługa błędów
            RETURN 'Wystąpił problem, spróbuj ponownie później.';
    END;
END;
$$;


ALTER FUNCTION public.wystaw_pojazd_na_sprzedaz(id_pojazdu_param integer) OWNER TO postgres;

--
-- Name: zakoncz_serwis(integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.zakoncz_serwis(id_pojazdu_param integer, id_konta_param integer) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE

    -- Deklaracja zmiennych lokalnych
    id_pracownika_ret INT;
    id_serwisu_ret INT;
BEGIN

    -- Rozpoczęcie transakcji
    BEGIN
	
        -- Znalezienie ID pracownika na podstawie ID konta
        SELECT id_pracownika INTO id_pracownika_ret
        FROM pracownicy
        WHERE id_konta = id_konta_param;
        
        -- Sprawdzenie, czy znaleziono pracownika
        IF id_pracownika_ret IS NULL THEN
            RETURN 'Nie znaleziono odpowiedniego pracownika.';
        END IF;

        -- Znalezienie aktywnego wpisu serwisowego
        SELECT id_serwisu INTO id_serwisu_ret
        FROM serwisowane_pojazdy
        WHERE id_pojazdu = id_pojazdu_param
        AND id_pracownika = id_pracownika_ret
        AND data_konca_serwisu IS NULL
        ORDER BY data_poczatku_serwisu DESC, id_serwisu DESC
        LIMIT 1;

        -- Sprawdzenie, czy znaleziono wpis serwisowy
        IF id_serwisu_ret IS NULL THEN
            RETURN 'Nie znaleziono wpisu serwisowanego pojazdu.';
        END IF;

        -- Aktualizacja statusu pojazdu
        UPDATE pojazdy
        SET status_pojazdu = 'W bazie'
        WHERE id_pojazdu = id_pojazdu_param;

        -- Aktualizacja wpisu serwisowego
        UPDATE serwisowane_pojazdy
        SET status_serwisu = 'Zakończony', data_konca_serwisu = NOW()
        WHERE id_serwisu = id_serwisu_ret;

        -- Zakończenie transakcji
        RETURN 'Serwis pojazdu zakończony pomyślnie.';
    EXCEPTION
        WHEN OTHERS THEN
		
            -- Obsługa błędów
            RETURN 'Wystąpił problem, spróbuj ponownie później.';
    END;
END;
$$;


ALTER FUNCTION public.zakoncz_serwis(id_pojazdu_param integer, id_konta_param integer) OWNER TO postgres;

--
-- Name: zakoncz_sprzedaz(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.zakoncz_sprzedaz(id_pojazdu_param integer) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE

	-- Deklaracja zmiennych lokalnych
    id_ogloszenia_ret INT;
BEGIN

    -- Rozpoczęcie transakcji
    BEGIN
	
        -- Znalezienie aktywnego wpisu sprzedaży
        SELECT id_ogloszenia INTO id_ogloszenia_ret 
        FROM wystawione_pojazdy_sprzedaz
        WHERE id_pojazdu = id_pojazdu_param AND data_zakonczenia IS NULL
        ORDER BY data_wystawienia DESC, id_ogloszenia DESC
        LIMIT 1;

        -- Sprawdzenie, czy znaleziono wpis sprzedaży
        IF id_ogloszenia_ret IS NULL THEN
            RETURN 'Nie znaleziono wpisu wystawionego pojazdu.';
        END IF;

        -- Aktualizacja statusu pojazdu
        UPDATE pojazdy 
        SET status_pojazdu = 'W bazie' 
        WHERE id_pojazdu = id_pojazdu_param;

        -- Aktualizacja wpisu sprzedaży
        UPDATE wystawione_pojazdy_sprzedaz 
        SET status_ogloszenia = 'Zakończone', data_zakonczenia = NOW()
        WHERE id_ogloszenia = id_ogloszenia_ret;

        -- Zakończenie transakcji
        RETURN 'Sprzedaż pojazdu zakończona pomyślnie.';
    EXCEPTION
        WHEN OTHERS THEN
		
            -- Obsługa błędów
            RETURN 'Wystąpił problem, spróbuj ponownie później.';
    END;
END;
$$;


ALTER FUNCTION public.zakoncz_sprzedaz(id_pojazdu_param integer) OWNER TO postgres;

--
-- Name: zakup_pojazdu(integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.zakup_pojazdu(id_pojazdu_param integer, id_konta_param integer) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE

    -- Deklaracja zmiennych lokalnych
    id_wlasciciela_ret INT;
    cena_pojazdu_ret NUMERIC;
    stan_konta_ret NUMERIC;
    nowy_id_pojazdu_ret INT;
    dane_pojazdu_ret RECORD;
	zdjecie_ret RECORD;
BEGIN

    -- Rozpoczęcie transakcji
    BEGIN
	
        -- Sprawdzenie, czy konto nie jest pracownikiem
        IF (SELECT typ_konta FROM konta WHERE id_konta = id_konta_param) = 'pracownik' THEN
            RETURN 'Nie znaleziono powiązanego konta.';
        END IF;

        -- Pobranie danych klienta
        SELECT stan_konta INTO stan_konta_ret FROM klienci WHERE id_konta = id_konta_param;
        IF stan_konta_ret IS NULL THEN
            RETURN 'Nie znaleziono powiązanego konta klienta.';
        END IF;

        -- Pobranie danych pojazdu
        SELECT * INTO dane_pojazdu_ret FROM pojazdy WHERE id_pojazdu = id_pojazdu_param;
        IF dane_pojazdu_ret IS NULL THEN
            RETURN 'Nie znaleziono pojazdu.';
        END IF;

        -- Sprawdzenie, czy wystawiony pojazd jest własnością klienta
        IF dane_pojazdu_ret.id_wlasciciela = id_konta_param THEN
            RETURN 'Pojazd jest już twoją własnością.';
        END IF;

        -- Sprawdzenie, czy wystawiony pojazd jest dostępny
        IF NOT EXISTS (SELECT 1 FROM wystawione_pojazdy_sprzedaz WHERE id_pojazdu = id_pojazdu_param AND data_zakonczenia IS NULL) THEN
            RETURN 'Nie znaleziono wpisu wystawionego pojazdu.';
        END IF;

        -- Sprawdzenie, czy klient ma wystarczające środki
        IF stan_konta_ret < dane_pojazdu_ret.cena THEN
            RETURN 'Nie masz wystarczających środków na koncie.';
        END IF;

        -- Aktualizacja stanu konta klienta
        UPDATE klienci SET stan_konta = stan_konta_ret - dane_pojazdu_ret.cena WHERE id_konta = id_konta_param;

        -- Aktualizacja statusu pojazdu
        UPDATE pojazdy SET status_pojazdu = 'Sprzedany' WHERE id_pojazdu = id_pojazdu_param;

        -- Rejestracja sprzedaży
        INSERT INTO sprzedane_pojazdy (id_pojazdu, id_kupujacego, data_sprzedazy) VALUES (id_pojazdu_param, id_konta_param, NOW());

        -- Aktualizacja statusu ogłoszenia
        UPDATE wystawione_pojazdy_sprzedaz SET status_ogloszenia = 'Zakończone', data_zakonczenia = NOW() WHERE id_pojazdu = id_pojazdu_param;

        -- Tworzenie nowego pojazdu w bazie na nowego właściciela
        INSERT INTO pojazdy (vin, id_cechy_pojazdu, rok_produkcji, przebieg, pojemnosc_silnika, moc_silnika, rodzaj_paliwa, liczba_drzwi, liczba_miejsc, cena, id_wlasciciela, status_pojazdu)
        VALUES (dane_pojazdu_ret.vin, dane_pojazdu_ret.id_cechy_pojazdu, dane_pojazdu_ret.rok_produkcji, dane_pojazdu_ret.przebieg, dane_pojazdu_ret.pojemnosc_silnika, dane_pojazdu_ret.moc_silnika, dane_pojazdu_ret.rodzaj_paliwa, dane_pojazdu_ret.liczba_drzwi, dane_pojazdu_ret.liczba_miejsc, dane_pojazdu_ret.cena, id_konta_param, 'W bazie')
        RETURNING id_pojazdu INTO nowy_id_pojazdu_ret;

        -- Kopiowanie zdjęć pojazdu do nowego pojazdu
        FOR zdjecie_ret IN SELECT nazwa_zdjecia FROM zdjecia_pojazdow WHERE id_pojazdu = id_pojazdu_param LOOP
            INSERT INTO zdjecia_pojazdow (id_pojazdu, nazwa_zdjecia)
            VALUES (nowy_id_pojazdu_ret, zdjecie_ret.nazwa_zdjecia);
        END LOOP;

        -- Zakończenie transakcji
        RETURN 'Pojazd został pomyślnie zakupiony.';
		
    EXCEPTION
        WHEN OTHERS THEN
		
            -- Obsługa błędów
            RETURN 'Wystąpił problem, spróbuj ponownie później.';
    END;
END;
$$;


ALTER FUNCTION public.zakup_pojazdu(id_pojazdu_param integer, id_konta_param integer) OWNER TO postgres;

--
-- Name: zarejestruj_uzytkownika(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.zarejestruj_uzytkownika(imie_param character varying, nazwisko_param character varying, numer_telefonu_param character varying, email_param character varying, ulica_param character varying, numer_domu_param character varying, kod_pocztowy_param character varying, miejscowosc_param character varying, kraj_param character varying, login_param character varying, haslo_param character varying) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE

    -- Deklaracja zmiennych lokalnych
    id_danych_ret INT;
    id_konta_ret INT;
BEGIN

    -- Rozpoczęcie transakcji
    BEGIN
	
        -- Tworzenie rekordu w tabeli 'dane'
        INSERT INTO dane (imie, nazwisko, numer_telefonu, email, ulica, numer_domu, kod_pocztowy, miejscowosc, kraj)
        VALUES (imie_param, nazwisko_param, numer_telefonu_param, email_param, ulica_param, numer_domu_param, kod_pocztowy_param, miejscowosc_param, kraj_param)
        RETURNING id_danych INTO id_danych_ret;

        -- Tworzenie rekordu w tabeli 'konta'
        INSERT INTO konta (login, haslo, typ_konta, id_danych)
        VALUES (login_param, haslo_param, 'klient', id_danych_ret)
        RETURNING id_konta INTO id_konta_ret;

        -- Tworzenie rekordu w tabeli 'klienci'
        INSERT INTO klienci (id_konta, stan_konta)
        VALUES (id_konta_ret, 0);

        -- Zakończenie transakcji
        RETURN 'Zarejestrowano konto pomyślnie.';
    EXCEPTION
        WHEN UNIQUE_VIOLATION THEN
		
            -- Obsługa błędów unikalności
            RETURN 'Podany Login lub Email jest już zajęty.';
        WHEN OTHERS THEN
		
            -- Obsługa pozostałych błędów
            RETURN 'Przepraszamy, wystąpił problem z rejestracją, spróbuj ponownie później.';
    END;
END;
$$;


ALTER FUNCTION public.zarejestruj_uzytkownika(imie_param character varying, nazwisko_param character varying, numer_telefonu_param character varying, email_param character varying, ulica_param character varying, numer_domu_param character varying, kod_pocztowy_param character varying, miejscowosc_param character varying, kraj_param character varying, login_param character varying, haslo_param character varying) OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: cechy_pojazdu; Type: TABLE; Schema: public; Owner: uzytkownik
--

CREATE TABLE public.cechy_pojazdu (
    id_cechy_pojazdu bigint NOT NULL,
    marka character varying(255) NOT NULL,
    model character varying(255) NOT NULL,
    nadwozie character varying(255) NOT NULL
);


ALTER TABLE public.cechy_pojazdu OWNER TO uzytkownik;

--
-- Name: cechy_pojazdu_id_cechy_pojazdu_seq; Type: SEQUENCE; Schema: public; Owner: uzytkownik
--

CREATE SEQUENCE public.cechy_pojazdu_id_cechy_pojazdu_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cechy_pojazdu_id_cechy_pojazdu_seq OWNER TO uzytkownik;

--
-- Name: cechy_pojazdu_id_cechy_pojazdu_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: uzytkownik
--

ALTER SEQUENCE public.cechy_pojazdu_id_cechy_pojazdu_seq OWNED BY public.cechy_pojazdu.id_cechy_pojazdu;


--
-- Name: dane; Type: TABLE; Schema: public; Owner: uzytkownik
--

CREATE TABLE public.dane (
    id_danych bigint NOT NULL,
    imie character varying(255) NOT NULL,
    nazwisko character varying(255) NOT NULL,
    numer_telefonu character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    ulica character varying(255) NOT NULL,
    numer_domu character varying(255) NOT NULL,
    kod_pocztowy character varying(255) NOT NULL,
    miejscowosc character varying(255) NOT NULL,
    kraj character varying(255) NOT NULL
);


ALTER TABLE public.dane OWNER TO uzytkownik;

--
-- Name: dane_id_danych_seq; Type: SEQUENCE; Schema: public; Owner: uzytkownik
--

CREATE SEQUENCE public.dane_id_danych_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.dane_id_danych_seq OWNER TO uzytkownik;

--
-- Name: dane_id_danych_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: uzytkownik
--

ALTER SEQUENCE public.dane_id_danych_seq OWNED BY public.dane.id_danych;


--
-- Name: klienci; Type: TABLE; Schema: public; Owner: uzytkownik
--

CREATE TABLE public.klienci (
    id_klienta bigint NOT NULL,
    id_konta bigint NOT NULL,
    stan_konta numeric(10,2) NOT NULL
);


ALTER TABLE public.klienci OWNER TO uzytkownik;

--
-- Name: klienci_id_klienta_seq; Type: SEQUENCE; Schema: public; Owner: uzytkownik
--

CREATE SEQUENCE public.klienci_id_klienta_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.klienci_id_klienta_seq OWNER TO uzytkownik;

--
-- Name: klienci_id_klienta_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: uzytkownik
--

ALTER SEQUENCE public.klienci_id_klienta_seq OWNED BY public.klienci.id_klienta;


--
-- Name: konta; Type: TABLE; Schema: public; Owner: uzytkownik
--

CREATE TABLE public.konta (
    id_konta bigint NOT NULL,
    login character varying(255) NOT NULL,
    haslo character varying(255) NOT NULL,
    typ_konta character varying(255) NOT NULL,
    id_danych bigint NOT NULL
);


ALTER TABLE public.konta OWNER TO uzytkownik;

--
-- Name: konta_id_konta_seq; Type: SEQUENCE; Schema: public; Owner: uzytkownik
--

CREATE SEQUENCE public.konta_id_konta_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.konta_id_konta_seq OWNER TO uzytkownik;

--
-- Name: konta_id_konta_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: uzytkownik
--

ALTER SEQUENCE public.konta_id_konta_seq OWNED BY public.konta.id_konta;


--
-- Name: kraje; Type: TABLE; Schema: public; Owner: uzytkownik
--

CREATE TABLE public.kraje (
    id_kraju bigint NOT NULL,
    nazwa character varying(255) NOT NULL
);


ALTER TABLE public.kraje OWNER TO uzytkownik;

--
-- Name: kraje_id_kraju_seq; Type: SEQUENCE; Schema: public; Owner: uzytkownik
--

CREATE SEQUENCE public.kraje_id_kraju_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.kraje_id_kraju_seq OWNER TO uzytkownik;

--
-- Name: kraje_id_kraju_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: uzytkownik
--

ALTER SEQUENCE public.kraje_id_kraju_seq OWNED BY public.kraje.id_kraju;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: uzytkownik
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO uzytkownik;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: uzytkownik
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO uzytkownik;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: uzytkownik
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: uzytkownik
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO uzytkownik;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: uzytkownik
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.personal_access_tokens_id_seq OWNER TO uzytkownik;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: uzytkownik
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: pojazdy; Type: TABLE; Schema: public; Owner: uzytkownik
--

CREATE TABLE public.pojazdy (
    id_pojazdu bigint NOT NULL,
    vin character varying(255) NOT NULL,
    id_cechy_pojazdu bigint NOT NULL,
    rok_produkcji integer NOT NULL,
    przebieg integer NOT NULL,
    pojemnosc_silnika integer NOT NULL,
    moc_silnika integer NOT NULL,
    rodzaj_paliwa character varying(255) NOT NULL,
    liczba_drzwi integer NOT NULL,
    liczba_miejsc integer NOT NULL,
    cena numeric(10,2) NOT NULL,
    id_wlasciciela bigint NOT NULL,
    status_pojazdu character varying(255) NOT NULL
);


ALTER TABLE public.pojazdy OWNER TO uzytkownik;

--
-- Name: pojazdy_id_pojazdu_seq; Type: SEQUENCE; Schema: public; Owner: uzytkownik
--

CREATE SEQUENCE public.pojazdy_id_pojazdu_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pojazdy_id_pojazdu_seq OWNER TO uzytkownik;

--
-- Name: pojazdy_id_pojazdu_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: uzytkownik
--

ALTER SEQUENCE public.pojazdy_id_pojazdu_seq OWNED BY public.pojazdy.id_pojazdu;


--
-- Name: pracownicy; Type: TABLE; Schema: public; Owner: uzytkownik
--

CREATE TABLE public.pracownicy (
    id_pracownika bigint NOT NULL,
    id_konta bigint NOT NULL,
    stanowisko character varying(255) NOT NULL
);


ALTER TABLE public.pracownicy OWNER TO uzytkownik;

--
-- Name: pracownicy_id_pracownika_seq; Type: SEQUENCE; Schema: public; Owner: uzytkownik
--

CREATE SEQUENCE public.pracownicy_id_pracownika_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pracownicy_id_pracownika_seq OWNER TO uzytkownik;

--
-- Name: pracownicy_id_pracownika_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: uzytkownik
--

ALTER SEQUENCE public.pracownicy_id_pracownika_seq OWNED BY public.pracownicy.id_pracownika;


--
-- Name: serwisowane_pojazdy; Type: TABLE; Schema: public; Owner: uzytkownik
--

CREATE TABLE public.serwisowane_pojazdy (
    id_serwisu bigint NOT NULL,
    id_pracownika bigint NOT NULL,
    id_pojazdu bigint NOT NULL,
    opis_usterki character varying(255) NOT NULL,
    data_poczatku_serwisu date NOT NULL,
    status_serwisu character varying(255) NOT NULL,
    data_konca_serwisu date
);


ALTER TABLE public.serwisowane_pojazdy OWNER TO uzytkownik;

--
-- Name: serwisowane_pojazdy_id_serwisu_seq; Type: SEQUENCE; Schema: public; Owner: uzytkownik
--

CREATE SEQUENCE public.serwisowane_pojazdy_id_serwisu_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.serwisowane_pojazdy_id_serwisu_seq OWNER TO uzytkownik;

--
-- Name: serwisowane_pojazdy_id_serwisu_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: uzytkownik
--

ALTER SEQUENCE public.serwisowane_pojazdy_id_serwisu_seq OWNED BY public.serwisowane_pojazdy.id_serwisu;


--
-- Name: sprzedane_pojazdy; Type: TABLE; Schema: public; Owner: uzytkownik
--

CREATE TABLE public.sprzedane_pojazdy (
    id_sprzedazy bigint NOT NULL,
    id_pojazdu bigint NOT NULL,
    id_kupujacego bigint NOT NULL,
    data_sprzedazy date NOT NULL
);


ALTER TABLE public.sprzedane_pojazdy OWNER TO uzytkownik;

--
-- Name: sprzedane_pojazdy_id_sprzedazy_seq; Type: SEQUENCE; Schema: public; Owner: uzytkownik
--

CREATE SEQUENCE public.sprzedane_pojazdy_id_sprzedazy_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sprzedane_pojazdy_id_sprzedazy_seq OWNER TO uzytkownik;

--
-- Name: sprzedane_pojazdy_id_sprzedazy_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: uzytkownik
--

ALTER SEQUENCE public.sprzedane_pojazdy_id_sprzedazy_seq OWNED BY public.sprzedane_pojazdy.id_sprzedazy;


--
-- Name: wystawione_pojazdy_sprzedaz; Type: TABLE; Schema: public; Owner: uzytkownik
--

CREATE TABLE public.wystawione_pojazdy_sprzedaz (
    id_ogloszenia bigint NOT NULL,
    id_pojazdu bigint NOT NULL,
    data_wystawienia date NOT NULL,
    status_ogloszenia character varying(255) NOT NULL,
    data_zakonczenia date
);


ALTER TABLE public.wystawione_pojazdy_sprzedaz OWNER TO uzytkownik;

--
-- Name: wystawione_pojazdy_sprzedaz_id_ogloszenia_seq; Type: SEQUENCE; Schema: public; Owner: uzytkownik
--

CREATE SEQUENCE public.wystawione_pojazdy_sprzedaz_id_ogloszenia_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.wystawione_pojazdy_sprzedaz_id_ogloszenia_seq OWNER TO uzytkownik;

--
-- Name: wystawione_pojazdy_sprzedaz_id_ogloszenia_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: uzytkownik
--

ALTER SEQUENCE public.wystawione_pojazdy_sprzedaz_id_ogloszenia_seq OWNED BY public.wystawione_pojazdy_sprzedaz.id_ogloszenia;


--
-- Name: zdjecia_pojazdow; Type: TABLE; Schema: public; Owner: uzytkownik
--

CREATE TABLE public.zdjecia_pojazdow (
    id_zdjecia bigint NOT NULL,
    id_pojazdu bigint NOT NULL,
    nazwa_zdjecia character varying(255) NOT NULL
);


ALTER TABLE public.zdjecia_pojazdow OWNER TO uzytkownik;

--
-- Name: zdjecia_pojazdow_id_zdjecia_seq; Type: SEQUENCE; Schema: public; Owner: uzytkownik
--

CREATE SEQUENCE public.zdjecia_pojazdow_id_zdjecia_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.zdjecia_pojazdow_id_zdjecia_seq OWNER TO uzytkownik;

--
-- Name: zdjecia_pojazdow_id_zdjecia_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: uzytkownik
--

ALTER SEQUENCE public.zdjecia_pojazdow_id_zdjecia_seq OWNED BY public.zdjecia_pojazdow.id_zdjecia;


--
-- Name: cechy_pojazdu id_cechy_pojazdu; Type: DEFAULT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.cechy_pojazdu ALTER COLUMN id_cechy_pojazdu SET DEFAULT nextval('public.cechy_pojazdu_id_cechy_pojazdu_seq'::regclass);


--
-- Name: dane id_danych; Type: DEFAULT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.dane ALTER COLUMN id_danych SET DEFAULT nextval('public.dane_id_danych_seq'::regclass);


--
-- Name: klienci id_klienta; Type: DEFAULT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.klienci ALTER COLUMN id_klienta SET DEFAULT nextval('public.klienci_id_klienta_seq'::regclass);


--
-- Name: konta id_konta; Type: DEFAULT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.konta ALTER COLUMN id_konta SET DEFAULT nextval('public.konta_id_konta_seq'::regclass);


--
-- Name: kraje id_kraju; Type: DEFAULT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.kraje ALTER COLUMN id_kraju SET DEFAULT nextval('public.kraje_id_kraju_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: pojazdy id_pojazdu; Type: DEFAULT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.pojazdy ALTER COLUMN id_pojazdu SET DEFAULT nextval('public.pojazdy_id_pojazdu_seq'::regclass);


--
-- Name: pracownicy id_pracownika; Type: DEFAULT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.pracownicy ALTER COLUMN id_pracownika SET DEFAULT nextval('public.pracownicy_id_pracownika_seq'::regclass);


--
-- Name: serwisowane_pojazdy id_serwisu; Type: DEFAULT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.serwisowane_pojazdy ALTER COLUMN id_serwisu SET DEFAULT nextval('public.serwisowane_pojazdy_id_serwisu_seq'::regclass);


--
-- Name: sprzedane_pojazdy id_sprzedazy; Type: DEFAULT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.sprzedane_pojazdy ALTER COLUMN id_sprzedazy SET DEFAULT nextval('public.sprzedane_pojazdy_id_sprzedazy_seq'::regclass);


--
-- Name: wystawione_pojazdy_sprzedaz id_ogloszenia; Type: DEFAULT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.wystawione_pojazdy_sprzedaz ALTER COLUMN id_ogloszenia SET DEFAULT nextval('public.wystawione_pojazdy_sprzedaz_id_ogloszenia_seq'::regclass);


--
-- Name: zdjecia_pojazdow id_zdjecia; Type: DEFAULT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.zdjecia_pojazdow ALTER COLUMN id_zdjecia SET DEFAULT nextval('public.zdjecia_pojazdow_id_zdjecia_seq'::regclass);


--
-- Data for Name: cechy_pojazdu; Type: TABLE DATA; Schema: public; Owner: uzytkownik
--

COPY public.cechy_pojazdu (id_cechy_pojazdu, marka, model, nadwozie) FROM stdin;
1	Toyota	Corolla	Sedan
2	Toyota	Corolla	Hatchback
5	Toyota	RAV4	SUV
3	Toyota	Yaris	Hatchback
4	Toyota	Camry	Sedan
6	Toyota	C-HR	SUV
7	Toyota	Highlander	SUV
8	Toyota	Sienna	Van
9	Toyota	Tacoma	Pickup
10	Toyota	Prius	Hatchback
11	Toyota	Avalon	Sedan
12	Honda	Civic	Sedan
13	Honda	Civic	Hatchback
14	Honda	Accord	Sedan
15	Honda	Accord	Coupe
16	Honda	CR-V	SUV
17	Honda	HR-V	SUV
18	Honda	Pilot	SUV
19	Honda	Fit	Hatchback
20	Honda	Odyssey	Van
21	Honda	Ridgeline	Pickup
22	Ford	Focus	Sedan
23	Ford	Focus	Hatchback
24	Ford	Fiesta	Sedan
25	Ford	Fiesta	Hatchback
26	Ford	Fusion	Sedan
27	Ford	Fusion	Hatchback
28	Ford	Mustang	Coupe
29	Ford	Mustang	Convertible
30	Ford	Escape	SUV
31	Ford	Explorer	SUV
32	Chevrolet	Malibu	Sedan
33	Chevrolet	Cruze	Sedan
34	Chevrolet	Impala	Sedan
35	Chevrolet	Spark	Hatchback
36	Chevrolet	Sonic	Sedan
37	Chevrolet	Volt	Hatchback
38	Chevrolet	Camaro	Coupe
39	Chevrolet	Camaro	Convertible
40	Chevrolet	Equinox	SUV
41	Chevrolet	Traverse	SUV
42	Volkswagen	Golf	Hatchback
43	Volkswagen	Passat	Sedan
44	Volkswagen	Jetta	Sedan
45	Volkswagen	Tiguan	SUV
46	Volkswagen	Atlas	SUV
47	Volkswagen	Arteon	Sedan
48	Volkswagen	Beetle	Hatchback
49	Volkswagen	Touareg	SUV
50	Volkswagen	ID.4	SUV
51	Volkswagen	Up	Hatchback
52	Audi	A1	Hatchback
53	Audi	A3	Hatchback
54	Audi	A4	Sedan
55	Audi	A5	Coupe
56	Audi	A6	Sedan
57	Audi	A7	Sedan
58	Audi	Q3	SUV
59	Audi	Q5	SUV
60	Audi	Q7	SUV
61	Audi	TT	Coupe
62	BMW	1 Series	Hatchback
63	BMW	3 Series	Sedan
64	BMW	5 Series	Sedan
65	BMW	X1	SUV
66	BMW	X3	SUV
67	BMW	X5	SUV
68	BMW	X7	SUV
69	BMW	2 Series	Coupe
70	BMW	4 Series	Coupe
71	BMW	6 Series	Coupe
72	Hyundai	Sonata	Sedan
73	Hyundai	i30	Hatchback
74	Hyundai	Elantra	Sedan
75	Hyundai	Veloster	Hatchback
76	Hyundai	Tucson	SUV
77	Hyundai	Santa Fe	SUV
78	Hyundai	Kona	SUV
79	Hyundai	Ioniq	Hatchback
80	Hyundai	Accent	Sedan
81	Hyundai	Venue	SUV
82	Tesla	Model S	Sedan
83	Tesla	Model 3	Sedan
84	Tesla	Model X	SUV
85	Tesla	Model Y	SUV
86	Tesla	Roadster	Cabrio
87	Tesla	Cybertruck	Pickup
88	Tesla	Semi	Truck
89	Tesla	Compact	Hatchback
90	Tesla	Tesla van	Van
91	Land Rover	Range Rover	SUV
92	Land Rover	Range Rover Evoque	SUV
93	Land Rover	Range Rover Velar	SUV
94	Land Rover	Range Rover Classic	SUV
95	Land Rover	Range Rover Sport	SUV
96	Land Rover	Range Rover Sport SVR	SUV
97	Land Rover	Defender	SUV
98	Land Rover	Discovery	SUV
99	Land Rover	Discovery Sport	SUV
100	Land Rover	Freelander	SUV
101	Mazda	2	Hatchback
102	Mazda	3	Hatchback
103	Mazda	5	MPV
104	Mazda	6	Sedan
105	Mazda	CX-3	SUV
106	Mazda	CX-5	SUV
107	Mazda	CX-9	SUV
108	Mazda	CX-30	SUV
109	Mazda	MX-5	Cabrio
110	Mazda	RX-8	Coupe
111	Volvo	S60	Sedan
112	Volvo	S90	Sedan
113	Volvo	V60	Wagon
114	Volvo	V90	Wagon
115	Volvo	XC40	SUV
116	Volvo	XC60	SUV
409	Mercedes-Benz	G-Class	Hatchback
410	Mercedes-Benz	G-Class	SUV
411	Mercedes-Benz	CLA-Class	Coupe
412	Mercedes-Benz	GLA-Class	SUV
413	Mercedes-Benz	GLC-Class	Coupe
414	Mercedes-Benz	GLC-Class	SUV
415	Mercedes-Benz	GLE-Class	Coupe
416	Mercedes-Benz	GLE-Class	SUV
417	Mercedes-Benz	GLS-Class	Coupe
418	Mercedes-Benz	GLS-Class	SUV
117	Volvo	XC90	SUV
118	Volvo	C30	Hatchback
119	Volvo	V40	Hatchback
120	Volvo	V40 Cross Country	Hatchback
121	Abarth	595	Hatchback
122	Abarth	124 Spider	Cabrio
123	Abarth	695	Hatchback
124	Abarth	500X	SUV
125	Abarth	595C	Cabrio
126	Seat	Ibiza	Hatchback
127	Seat	Leon	Hatchback
128	Seat	Arona	SUV
129	Seat	Ateca	SUV
130	Seat	Tarraco	SUV
131	Seat	Toledo	Sedan
132	Seat	Alhambra	MPV
133	Seat	Mii	Hatchback
134	Seat	Exeo	Sedan
135	Seat	Cupra Formentor	SUV
136	Skoda	Octavia	Hatchback
137	Skoda	Fabia	Hatchback
138	Skoda	Superb	Sedan
139	Skoda	Kodiaq	SUV
140	Skoda	Karoq	SUV
141	Skoda	Scala	Hatchback
142	Skoda	Yeti	SUV
143	Skoda	Rapid	Sedan
144	Skoda	Citigo	Hatchback
145	Skoda	Enyaq	SUV
146	Peugeot	208	Hatchback
147	Peugeot	208	SUV
148	Peugeot	308	Hatchback
149	Peugeot	308	SUV
150	Peugeot	508	Sedan
151	Peugeot	508	SUV
152	Peugeot	Partner	Van
153	Peugeot	Expert	Van
154	Peugeot	Boxer	Van
155	Peugeot	Rifter	MPV
156	Renault	Clio	Hatchback
157	Renault	Megane	Hatchback
158	Renault	Talisman	Sedan
159	Renault	Captur	SUV
160	Renault	Captur	Van
161	Renault	Kadjar	SUV
162	Renault	Kadjar	Van
163	Renault	Koleos	SUV
164	Renault	Kangoo	SUV
165	Renault	Kangoo	Van
166	Renault	Trafic	Van
167	Renault	Master	Van
168	Renault	Scenic	MPV
169	Opel	Corsa	Hatchback
170	Opel	Astra	Hatchback
171	Opel	Insignia	Sedan
172	Opel	Insignia	SUV
173	Opel	Mokka	SUV
174	Opel	Crossland	SUV
175	Opel	Grandland	SUV
176	Opel	Combo	Van
177	Opel	Vivaro	Van
178	Opel	Movano	Van
179	Opel	Movano	MPV
180	Opel	Zafira	Van
181	Opel	Zafira	MPV
182	Isuzu	D-Max	Pickup
183	Isuzu	MU-X	SUV
184	Isuzu	Trooper	SUV
185	Isuzu	Rodeo	Pickup
186	Isuzu	Ascender	SUV
187	Isuzu	Axiom	SUV
188	Isuzu	VehiCROSS	SUV
189	Isuzu	i-Series	Pickup
190	Isuzu	Gemini	Hatchback
191	Isuzu	Piazza	Coupe
192	Iveco	Daily	Van
193	Iveco	Eurocargo	Truck
194	Iveco	Stralis	Truck
195	Iveco	Trakker	Truck
196	Iveco	PowerStar	Truck
197	Iveco	TurboDaily	Van
198	Iveco	Eurostar	Truck
199	Iveco	Eurotrakker	Truck
200	Iveco	Massif	SUV
201	Iveco	Daily 4x4	Van
202	Alfa Romeo	Giulia	Sedan
203	Alfa Romeo	Giulia	SUV
204	Alfa Romeo	Stelvio	Sedan
205	Alfa Romeo	Stelvio	SUV
206	Alfa Romeo	Giulietta	Hatchback
207	Alfa Romeo	4C	Coupe
208	Alfa Romeo	Tonale	Hatchback
209	Alfa Romeo	Tonale	SUV
210	Alfa Romeo	Brera	Coupe
211	Alfa Romeo	159	Sedan
212	Alfa Romeo	159	Hatchback
213	Alfa Romeo	GT	Coupe
214	Alfa Romeo	MiTo	Hatchback
215	Alfa Romeo	Spider	Cabrio
216	Alfa Romeo	Spider	Coupe
217	Bentley	Continental GT	Coupe
218	Bentley	Flying Spur	Sedan
219	Bentley	Bentayga	Hatchback
220	Bentley	Bentayga	SUV
221	Bentley	Mulsanne	Sedan
222	Bentley	Continental GTC	Cabrio
223	Bentley	Arnage	Sedan
224	Bentley	Brooklands	Coupe
225	Bentley	Brooklands	Cabrio
226	Bentley	Azure	Coupe
227	Bentley	Azure	Cabrio
228	Bentley	Turbo R	Sedan
229	Bentley	Mulsanne Speed	Sedan
230	SsangYong	Tivoli	SUV
231	SsangYong	Korando	SUV
232	SsangYong	Rexton	SUV
233	SsangYong	Rexton	Pickup
234	SsangYong	Musso	SUV
235	SsangYong	Musso	Pickup
236	SsangYong	Actyon	SUV
237	SsangYong	XLV	SUV
238	SsangYong	Kyron	SUV
239	SsangYong	Kyron	Pickup
240	SsangYong	Rodius	MPV
241	SsangYong	Rodius	Pickup
242	SsangYong	Korando Sports	Pickup
243	Suzuki	Swift	Hatchback
244	Suzuki	Vitara	SUV
245	Suzuki	Jimny	SUV
246	Suzuki	Ignis	Hatchback
247	Suzuki	S-Cross	SUV
248	Suzuki	Baleno	Hatchback
249	Suzuki	SX4	Sedan
250	Suzuki	Celerio	Hatchback
251	Suzuki	Splash	Hatchback
252	Suzuki	Grand Vitara	SUV
253	Porsche	911	Coupe
254	Porsche	911	Cabrio
255	Porsche	Cayenne	SUV
256	Porsche	Panamera	Sedan
257	Porsche	Panamera	Coupe
258	Porsche	Macan	SUV
259	Porsche	Boxster	Cabrio
260	Porsche	Cayman	Coupe
261	Porsche	Cayman	Cabrio
262	Porsche	Cayman	Sedan
263	Porsche	Taycan	Sedan
264	Porsche	718	Cabrio
265	Porsche	718	Coupe
266	Dodge	Charger	Sedan
267	Dodge	Challenger	Coupe
268	Dodge	Durango	SUV
269	Dodge	Durango	Hatchback
270	Dodge	Durango	Van
271	Dodge	Journey	SUV
272	Dodge	Journey	Hatchback
273	Dodge	Grand Caravan	Van
274	Dodge	Avenger	Sedan
275	Dodge	Dart	Sedan
276	Dodge	Dart	Hatchback
277	Dodge	Nitro	SUV
278	Dodge	Caliber	Hatchback
279	Dodge	Magnum	Wagon
280	Ferrari	488 GTB	Coupe
281	Ferrari	812 Superfast	Coupe
282	Ferrari	F8 Tributo	Coupe
283	Ferrari	SF90 Stradale	Coupe
284	Ferrari	Roma	Coupe
285	Ferrari	Portofino	Cabrio
286	Ferrari	GTC4Lusso	Hatchback
287	Ferrari	Monza SP1	Roadster
288	Ferrari	458 Italia	Coupe
289	Ferrari	LaFerrari	Coupe
290	Jaguar	XE	Sedan
291	Jaguar	XF	Sedan
292	Jaguar	XJ	Sedan
293	Jaguar	F-Type	Coupe
294	Jaguar	F-Type	SUV
295	Jaguar	F-Type	Sedan
296	Jaguar	E-Pace	Coupe
297	Jaguar	E-Pace	SUV
298	Jaguar	E-Pace	Sedan
299	Jaguar	I-Pace	Coupe
300	Jaguar	I-Pace	SUV
301	Jaguar	I-Pace	Sedan
302	Jaguar	X-Type	Coupe
303	Jaguar	X-Type	SUV
304	Jaguar	X-Type	Sedan
305	Jaguar	S-Type	Coupe
306	Jaguar	S-Type	SUV
307	Jaguar	S-Type	Sedan
308	Jeep	Wrangler	SUV
309	Jeep	Grand Cherokee	SUV
310	Jeep	Cherokee	SUV
311	Jeep	Compass	SUV
312	Jeep	Renegade	SUV
313	Jeep	Commander	SUV
314	Jeep	Patriot	SUV
315	Jeep	Liberty	SUV
316	Jeep	Liberty	Pickup
317	Jeep	Wagoneer	SUV
318	Jeep	Wagoneer	Pickup
319	Jeep	Gladiator	SUV
320	Jeep	Gladiator	Pickup
321	Cupra	Leon	Hatchback
322	Cupra	Ateca	SUV
323	Cupra	Ateca	Hatchback
324	Cupra	Formentor	SUV
325	Cupra	Leon	Wagon
326	Cupra	Ateca Limited Edition	SUV
327	Cupra	Leon Cupra	Wagon
328	Cupra	Leon Cupra	Hatchback
329	Cupra	Formentor VZ5	SUV
330	Cupra	Leon ST	Wagon
331	Mini	Cooper	Hatchback
332	Mini	Cooper	SUV
333	Mini	Countryman	Hatchback
334	Mini	Countryman	SUV
335	Mini	Clubman	Wagon
336	Mini	Convertible	Cabrio
337	Mini	Paceman	SUV
338	Mini	Coupe	Coupe
339	Mini	Roadster	Cabrio
340	Mini	Roadster	Coupe
341	Mini	Roadster	Sedan
342	Mini	Hardtop	Hatchback
343	Mini	Hardtop	SUV
344	Mini	John Cooper Works	Hatchback
345	Fiat	500	Hatchback
346	Fiat	500	SUV
347	Fiat	500	MPV
348	Fiat	Panda	Hatchback
349	Fiat	Tipo	Sedan
350	Fiat	Punto	Hatchback
351	Fiat	Doblo	Van
352	Fiat	Qubo	Van
353	Fiat	Talento	Van
354	Kia	Rio	Hatchback
355	Kia	Rio	SUV
356	Kia	Ceed	Hatchback
357	Kia	Ceed	SUV
358	Kia	Forte	Sedan
359	Kia	Optima	Coupe
360	Kia	Optima	Sedan
361	Kia	Stinger	Coupe
362	Kia	Stinger	Sedan
363	Kia	Sportage	SUV
364	Kia	Seltos	SUV
365	Kia	Soul	Hatchback
366	Kia	Telluride	SUV
367	Nissan	Altima	Sedan
368	Nissan	Sentra	Sedan
369	Nissan	Maxima	Sedan
370	Nissan	Altima	Coupe
371	Nissan	Sentra	Coupe
372	Nissan	Maxima	Coupe
373	Nissan	Altima	Cabrio
374	Nissan	Sentra	Cabrio
375	Nissan	Maxima	Cabrio
376	Nissan	Micra	Hatchback
377	Nissan	Micra	SUV
378	Nissan	Rogue	Hatchback
379	Nissan	Rogue	SUV
380	Nissan	Murano	SUV
381	Nissan	Pathfinder	SUV
382	Nissan	Armada	SUV
383	Nissan	Leaf	Hatchback
384	Nissan	Leaf	SUV
385	Citroen	C3	Hatchback
386	Citroen	C3	Sedan
387	Citroen	C3	SUV
388	Citroen	C4	Hatchback
389	Citroen	C4	Sedan
390	Citroen	C4	SUV
391	Citroen	C5	Hatchback
392	Citroen	C5	Sedan
393	Citroen	C5	SUV
394	Citroen	Berlingo	Hatchback
395	Citroen	Berlingo	Van
396	Citroen	DS3	Hatchback
397	Citroen	DS4	Hatchback
398	Citroen	DS5	Hatchback
399	Dacia	Sandero	Hatchback
400	Dacia	Logan	Sedan
401	Dacia	Duster	SUV
402	Dacia	Dokker	Van
403	Dacia	Lodgy	MPV
404	Mercedes-Benz	C-Class	Sedan
405	Mercedes-Benz	E-Class	Sedan
406	Mercedes-Benz	S-Class	Sedan
407	Mercedes-Benz	A-Class	Hatchback
408	Mercedes-Benz	A-Class	SUV
\.


--
-- Data for Name: dane; Type: TABLE DATA; Schema: public; Owner: uzytkownik
--

COPY public.dane (id_danych, imie, nazwisko, numer_telefonu, email, ulica, numer_domu, kod_pocztowy, miejscowosc, kraj) FROM stdin;
2	Jan	Nowak	987654321	jan.nowak@example.com	Długa	47B	00-002	Kraków	Polska
3	Marta	Wiśniewska	123123123	marta.wisniewska@example.com	Krótka	3C	00-003	Łódź	Polska
4	Karol	Lewandowski	321321321	karol.lewandowski@example.com	Polna	99	00-004	Gdańsk	Polska
5	Agnieszka	Kaczmarek	456456456	agnieszka.kaczmarek@example.com	Zakątek	15	00-005	Poznań	Polska
6	Robert	Kamiński	654654654	robert.kaminski@example.com	Szeroka	27	00-006	Wrocław	Polska
7	Magdalena	Zając	789789789	magdalena.zajac@example.com	Leśna	8	00-007	Szczecin	Polska
8	Piotr	Kowal	987987987	piotr.kowal@example.com	Morska	64	00-008	Gdynia	Polska
9	Dorota	Mazur	111222333	dorota.mazur@example.com	Główna	4	00-009	Katowice	Polska
10	Tomasz	Klimek	333222111	tomasz.klimek@example.com	Parkowa	10	00-010	Rzeszów	Polska
11	Michalina	Kiepska	444555666	michalina.kiepska@example.com	Dębowa	17E	00-007	Szczecin	Polska
12	Michał	Kępski	666555444	m.kepski@example.com	Rejtana	321	00-005	Poznań	Polska
13	Maksymilian	Przypadek	777888999	maks.przypadek@example.com	Szkolna	5	00-004	Gdańsk	Polska
14	Anna	Mała	999888777	mala.ania@example.com	3 Maja	86	00-005	Poznań	Polska
1	Anna	Kowalska	123456789	anna.kowalska@example.com	Mickiewicza	13A	00-001	Warszawa	Polska
\.


--
-- Data for Name: klienci; Type: TABLE DATA; Schema: public; Owner: uzytkownik
--

COPY public.klienci (id_klienta, id_konta, stan_konta) FROM stdin;
3	3	39491.47
5	7	103412.96
6	8	56128.32
7	9	85257.06
8	10	134044.80
9	11	5204.18
10	13	68189.70
4	4	102800.94
2	2	66379.48
1	1	130230.38
\.


--
-- Data for Name: konta; Type: TABLE DATA; Schema: public; Owner: uzytkownik
--

COPY public.konta (id_konta, login, haslo, typ_konta, id_danych) FROM stdin;
1	konto1	$2y$12$J7G9xWNDGeV0duNFIFsx7OMA9mZgHNyFE4SiLzWlYucnI9ypz0LIy	klient	1
2	konto2	$2y$12$gEQKnsotpfxD4Ke76MtUJejs/VWYtEfULntq511gjq73Gpw3QfY/e	klient	2
3	konto3	$2y$12$WgGYeHfQImVg81Vu4pCjbeYBflBC/VDf2fXOJt5XfYa269mggZlnK	klient	3
4	konto4	$2y$12$4QCrInI7JtreV0pEDNbepOiVW/aq65nAAW8Wak.vPxhaueRk3FWPS	klient	4
5	konto5	$2y$12$6QKzoWbY/sehdJ1VfAwije.cNuU9tuTH5URws8tpGEz9eUSwMkRu.	pracownik	5
6	konto6	$2y$12$Y570jywQA7b4n4HIwkqce.BxOWUS0//StqFXr8xqES7ncNmF1Xyru	pracownik	6
7	konto7	$2y$12$Xdhjzb/PMEFI8Jtj.JYdGOt6ckMTli2qFl/QLJGMNP065U.xZUy/2	klient	7
8	konto8	$2y$12$OxDbIywldkEVgaQL9imACOcXd.UfMgW2wf7Gk6/1DZFrgm2RKsqXq	klient	8
9	konto9	$2y$12$l73v3PTYDjUNdCyGGlWa5.eEjjs4D4dXWQwzQj4LplhXh.JC1tsfK	klient	9
10	konto10	$2y$12$X87PtM.2IZtGYHt8p6Fu/uepnb7pNI1ZPt8LTEJheJg6tveKpB.0a	klient	10
11	konto11	$2y$12$gMNvjO42MT.YNBpbOTzQJ.Dyxbaplob5wxVQaUPr1aM.5DkobGdqy	klient	11
12	konto12	$2y$12$xiAADqcjgcAE.WvMiswFT.Zs2SegixuetNwWEVfP2kZKRmnK6STYm	pracownik	12
13	konto13	$2y$12$aJ/FQ.pllK859Uy6piJvqOeIhwfYvmtNvCf6pzsUyde/JTkPtinsS	klient	13
14	konto14	$2y$12$Gx63mHsB10.TG3cfUJmpVeVULBf9c6Ll2k3lvQrthpwU.k/1cRUP6	pracownik	14
\.


--
-- Data for Name: kraje; Type: TABLE DATA; Schema: public; Owner: uzytkownik
--

COPY public.kraje (id_kraju, nazwa) FROM stdin;
1	Afganistan
2	Albania
3	Algieria
4	Andora
5	Angola
6	Antigua i Barbuda
7	Arabia Saudyjska
8	Argentyna
9	Armenia
10	Australia
11	Austria
12	Azerbejdżan
13	Bahamy
14	Bahrajn
15	Bangladesz
16	Barbados
17	Belgia
18	Belize
19	Benin
20	Bhutan
21	Białoruś
22	Boliwia
23	Bośnia i Hercegowina
24	Botswana
25	Brazylia
26	Brunei
27	Bułgaria
28	Burkina Faso
29	Burundi
30	Chile
31	Chiny
32	Chorwacja
33	Cypr
34	Czad
35	Czarnogóra
36	Czechy
37	Dania
38	Demokratyczna Republika Konga
39	Dominika
40	Dominikana
41	Dżibuti
42	Egipt
43	Ekwador
44	Erytrea
45	Estonia
46	Eswatini
47	Etiopia
48	Fidżi
49	Filipiny
50	Finlandia
51	Francja
52	Gabon
53	Gambia
54	Ghana
55	Grecja
56	Grenada
57	Gruzja
58	Gujana
59	Gwatemala
60	Gwinea
61	Gwinea Bissau
62	Gwinea Równikowa
63	Haiti
64	Hiszpania
65	Holandia
66	Honduras
67	Indie
68	Indonezja
69	Irak
70	Iran
71	Irlandia
72	Islandia
73	Izrael
74	Jamajka
75	Japonia
76	Jemen
77	Jordania
78	Kambodża
79	Kamerun
80	Kanada
81	Katar
82	Kazachstan
83	Kenia
84	Kirgistan
85	Kiribati
86	Kolumbia
87	Komory
88	Kongo
89	Korea Południowa
90	Korea Północna
91	Kostaryka
92	Kuba
93	Kuwejt
94	Laos
95	Lesotho
96	Liban
97	Liberia
98	Libia
99	Liechtenstein
100	Litwa
101	Luksemburg
102	Łotwa
103	Macedonia Północna
104	Madagaskar
105	Malawi
106	Malediwy
107	Malezja
108	Mali
109	Malta
110	Maroko
111	Mauretania
112	Mauritius
113	Meksyk
114	Mikronezja
115	Mołdawia
116	Monako
117	Mongolia
118	Mozambik
119	Namibia
120	Nauru
121	Nepal
122	Niemcy
123	Niger
124	Nigeria
125	Nikaragua
126	Norwegia
127	Nowa Zelandia
128	Oman
129	Pakistan
130	Palau
131	Panama
132	Papua-Nowa Gwinea
133	Paragwaj
134	Peru
135	Polska
136	Portugalia
137	Republika Południowej Afryki
138	Republika Środkowoafrykańska
139	Republika Zielonego Przylądka
140	Rosja
141	Rumunia
142	Rwanda
143	Saint Kitts i Nevis
144	Saint Lucia
145	Saint Vincent i Grenadyny
146	Salwador
147	Samoa
148	San Marino
149	Senegal
150	Serbia
151	Seszele
152	Sierra Leone
153	Singapur
154	Słowacja
155	Słowenia
156	Somalia
157	Sri Lanka
158	Sudan
159	Sudan Południowy
160	Surinam
161	Syria
162	Szwajcaria
163	Szwecja
164	Tadżykistan
165	Tajlandia
166	Tanzania
167	Timor Wschodni
168	Togo
169	Tonga
170	Trynidad i Tobago
171	Tunezja
172	Turcja
173	Turkmenistan
174	Tuvalu
175	Uganda
176	Ukraina
177	Urugwaj
178	Uzbekistan
179	Vanuatu
180	Watykan
181	Wenezuela
182	Węgry
183	Wielka Brytania
184	Wietnam
185	Włochy
186	Wybrzeże Kości Słoniowej
187	Wyspy Marshalla
188	Wyspy Salomona
189	Wyspy Świętego Tomasza i Książęca
190	Zambia
191	Zimbabwe
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: uzytkownik
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2019_12_14_000001_create_personal_access_tokens_table	1
2	2024_04_23_192549_create_table_dane	1
3	2024_04_23_192653_create_table_konta	1
4	2024_04_23_192732_create_table_klienci	1
5	2024_04_23_192750_create_table_pracownicy	1
6	2024_04_23_192813_create_table_cechy_pojazdu	1
7	2024_04_23_192913_create_table_pojazdy	1
8	2024_04_23_192938_create_table_serwisowane_pojazdy	1
9	2024_04_23_193003_create_table_sprzedane_pojazdy	1
10	2024_04_23_193035_create_table_wystawione_pojazdy_sprzedaz	1
11	2024_04_24_102153_drop_serwisowane_pojazdy_table	2
12	2024_04_24_102257_create_table_serwisowane_pojazdy	3
13	2024_04_26_073125_create_table_kraje	4
14	2024_04_26_074334_create_table_kraje	5
15	2024_04_26_215447_create_table_zdjecia_pojazdow	6
\.


--
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: uzytkownik
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: pojazdy; Type: TABLE DATA; Schema: public; Owner: uzytkownik
--

COPY public.pojazdy (id_pojazdu, vin, id_cechy_pojazdu, rok_produkcji, przebieg, pojemnosc_silnika, moc_silnika, rodzaj_paliwa, liczba_drzwi, liczba_miejsc, cena, id_wlasciciela, status_pojazdu) FROM stdin;
3	JTKJF5C77E3006451	180	2015	234581	2200	140	diesel	4	7	81500.00	2	W bazie
1	JM1BL1SF3A1278376	50	2010	126890	1800	140	benzyna	5	5	35000.00	3	W bazie
4	1B3LC56J68N136229	252	2009	265908	2000	114	benzyna + gaz	5	4	74000.00	3	W serwisie
5	3GCUKSEC1EG580856	42	2005	321876	1900	126	benzyna + gaz	5	5	29900.00	7	W serwisie
6	2G1WB55K569305562	63	2019	43707	3200	230	diesel	5	4	143000.00	10	W bazie
7	JM3KE4DY9E0335841	309	2012	156700	2600	198	benzyna	5	5	98000.00	9	W bazie
2	1FMCU9EG1AKB10553	165	2012	89371	2000	150	diesel	5	8	67000.00	2	W bazie
9	1J8GN28K29W598211	201	2006	324097	2300	162	diesel + gaz	5	5	68500.00	5	Sprzedany
10	4T1SK12E5RU870281	125	2017	40980	3000	240	benzyna	2	2	109900.00	9	W bazie
13	1J8GN28K29W598211	201	2006	324097	2300	162	diesel + gaz	5	5	68500.00	8	W bazie
11	2GE5RU8705809KBTY	87	2020	13800	3200	312	diesel	2	2	99900.00	9	W bazie
8	4A4AP3AU1FE003755	385	2004	358980	1400	110	diesel	4	5	34700.00	6	Na sprzedaż
12	2HJYK16586H589000	3	2010	210700	1000	87	benzyna + gaz	5	4	6700.00	4	Na sprzedaż
\.


--
-- Data for Name: pracownicy; Type: TABLE DATA; Schema: public; Owner: uzytkownik
--

COPY public.pracownicy (id_pracownika, id_konta, stanowisko) FROM stdin;
1	5	koordynator
2	6	admin
3	12	koordynator
4	14	koordynator
\.


--
-- Data for Name: serwisowane_pojazdy; Type: TABLE DATA; Schema: public; Owner: uzytkownik
--

COPY public.serwisowane_pojazdy (id_serwisu, id_pracownika, id_pojazdu, opis_usterki, data_poczatku_serwisu, status_serwisu, data_konca_serwisu) FROM stdin;
1	1	4	Zbita przednia szyba.	2023-04-19	W trakcie	\N
2	3	5	Skrzynia biegów mi nie działa. Proszę o szybką naprawę!!!	2023-04-19	W trakcie	\N
3	3	6	Proszę o wymianę świateł mijania.	2023-04-20	Zakończony	2023-04-24
4	4	7	Proszę o uzupełnienie płynu hamulcowego i chłodniczego.	2023-04-21	Zakończony	2023-04-23
\.


--
-- Data for Name: sprzedane_pojazdy; Type: TABLE DATA; Schema: public; Owner: uzytkownik
--

COPY public.sprzedane_pojazdy (id_sprzedazy, id_pojazdu, id_kupujacego, data_sprzedazy) FROM stdin;
1	9	8	2023-04-22
\.


--
-- Data for Name: wystawione_pojazdy_sprzedaz; Type: TABLE DATA; Schema: public; Owner: uzytkownik
--

COPY public.wystawione_pojazdy_sprzedaz (id_ogloszenia, id_pojazdu, data_wystawienia, status_ogloszenia, data_zakonczenia) FROM stdin;
2	9	2023-04-08	Zakończone	2023-04-22
3	11	2023-04-10	Zakończone	2023-04-15
1	8	2023-04-07	W trakcie	\N
4	12	2023-04-11	Zakończone	2024-06-03
\.


--
-- Data for Name: zdjecia_pojazdow; Type: TABLE DATA; Schema: public; Owner: uzytkownik
--

COPY public.zdjecia_pojazdow (id_zdjecia, id_pojazdu, nazwa_zdjecia) FROM stdin;
1	1	Volkswagen_ID.4_1.png
7	3	Opel_Zafira_1.png
8	3	Opel_Zafira_2.png
9	3	Opel_Zafira_3.png
10	4	Suzuki_GrandVitara_1.png
11	4	Suzuki_GrandVitara_2.png
12	4	Suzuki_GrandVitara_3.png
13	5	Volkswagen_Golf_1.png
14	5	Volkswagen_Golf_2.png
15	5	Volkswagen_Golf_3.png
16	6	BMW_3Series_1.png
17	6	BMW_3Series_2.png
18	6	BMW_3Series_3.png
19	7	Jeep_GrandCherokee_1.png
20	7	Jeep_GrandCherokee_2.png
21	7	Jeep_GrandCherokee_3.png
22	8	Citroen_C3_1.png
23	8	Citroen_C3_2.png
24	8	Citroen_C3_3.png
25	9	Iveco_Daily4x4_1.png
26	9	Iveco_Daily4x4_2.png
27	9	Iveco_Daily4x4_3.png
28	10	Abarth_595C_1.png
29	10	Abarth_595C_2.png
30	10	Abarth_595C_3.png
31	11	Tesla_Cybertruck_1.png
32	11	Tesla_Cybertruck_2.png
33	11	Tesla_Cybertruck_3.png
34	12	Toyota_Yaris_1.png
35	12	Toyota_Yaris_2.png
36	12	Toyota_Yaris_3.png
37	13	Iveco_Daily4x4_1.png
38	13	Iveco_Daily4x4_2.png
39	13	Iveco_Daily4x4_3.png
2	1	Volkswagen_ID.4_2.png
3	1	Volkswagen_ID.4_3.png
4	2	Renault_Kangoo_1.png
5	2	Renault_Kangoo_2.png
6	2	Renault_Kangoo_3.png
\.


--
-- Name: cechy_pojazdu_id_cechy_pojazdu_seq; Type: SEQUENCE SET; Schema: public; Owner: uzytkownik
--

SELECT pg_catalog.setval('public.cechy_pojazdu_id_cechy_pojazdu_seq', 452, true);


--
-- Name: dane_id_danych_seq; Type: SEQUENCE SET; Schema: public; Owner: uzytkownik
--

SELECT pg_catalog.setval('public.dane_id_danych_seq', 37, true);


--
-- Name: klienci_id_klienta_seq; Type: SEQUENCE SET; Schema: public; Owner: uzytkownik
--

SELECT pg_catalog.setval('public.klienci_id_klienta_seq', 21, true);


--
-- Name: konta_id_konta_seq; Type: SEQUENCE SET; Schema: public; Owner: uzytkownik
--

SELECT pg_catalog.setval('public.konta_id_konta_seq', 31, true);


--
-- Name: kraje_id_kraju_seq; Type: SEQUENCE SET; Schema: public; Owner: uzytkownik
--

SELECT pg_catalog.setval('public.kraje_id_kraju_seq', 191, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: uzytkownik
--

SELECT pg_catalog.setval('public.migrations_id_seq', 15, true);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: uzytkownik
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


--
-- Name: pojazdy_id_pojazdu_seq; Type: SEQUENCE SET; Schema: public; Owner: uzytkownik
--

SELECT pg_catalog.setval('public.pojazdy_id_pojazdu_seq', 56, true);


--
-- Name: pracownicy_id_pracownika_seq; Type: SEQUENCE SET; Schema: public; Owner: uzytkownik
--

SELECT pg_catalog.setval('public.pracownicy_id_pracownika_seq', 4, true);


--
-- Name: serwisowane_pojazdy_id_serwisu_seq; Type: SEQUENCE SET; Schema: public; Owner: uzytkownik
--

SELECT pg_catalog.setval('public.serwisowane_pojazdy_id_serwisu_seq', 26, true);


--
-- Name: sprzedane_pojazdy_id_sprzedazy_seq; Type: SEQUENCE SET; Schema: public; Owner: uzytkownik
--

SELECT pg_catalog.setval('public.sprzedane_pojazdy_id_sprzedazy_seq', 15, true);


--
-- Name: wystawione_pojazdy_sprzedaz_id_ogloszenia_seq; Type: SEQUENCE SET; Schema: public; Owner: uzytkownik
--

SELECT pg_catalog.setval('public.wystawione_pojazdy_sprzedaz_id_ogloszenia_seq', 20, true);


--
-- Name: zdjecia_pojazdow_id_zdjecia_seq; Type: SEQUENCE SET; Schema: public; Owner: uzytkownik
--

SELECT pg_catalog.setval('public.zdjecia_pojazdow_id_zdjecia_seq', 124, true);


--
-- Name: cechy_pojazdu cechy_pojazdu_pkey; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.cechy_pojazdu
    ADD CONSTRAINT cechy_pojazdu_pkey PRIMARY KEY (id_cechy_pojazdu);


--
-- Name: dane dane_email_unique; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.dane
    ADD CONSTRAINT dane_email_unique UNIQUE (email);


--
-- Name: dane dane_pkey; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.dane
    ADD CONSTRAINT dane_pkey PRIMARY KEY (id_danych);


--
-- Name: klienci klienci_pkey; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.klienci
    ADD CONSTRAINT klienci_pkey PRIMARY KEY (id_klienta);


--
-- Name: konta konta_login_unique; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.konta
    ADD CONSTRAINT konta_login_unique UNIQUE (login);


--
-- Name: konta konta_pkey; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.konta
    ADD CONSTRAINT konta_pkey PRIMARY KEY (id_konta);


--
-- Name: kraje kraje_pkey; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.kraje
    ADD CONSTRAINT kraje_pkey PRIMARY KEY (id_kraju);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: pojazdy pojazdy_pkey; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.pojazdy
    ADD CONSTRAINT pojazdy_pkey PRIMARY KEY (id_pojazdu);


--
-- Name: pracownicy pracownicy_pkey; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.pracownicy
    ADD CONSTRAINT pracownicy_pkey PRIMARY KEY (id_pracownika);


--
-- Name: serwisowane_pojazdy serwisowane_pojazdy_pkey; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.serwisowane_pojazdy
    ADD CONSTRAINT serwisowane_pojazdy_pkey PRIMARY KEY (id_serwisu);


--
-- Name: sprzedane_pojazdy sprzedane_pojazdy_pkey; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.sprzedane_pojazdy
    ADD CONSTRAINT sprzedane_pojazdy_pkey PRIMARY KEY (id_sprzedazy);


--
-- Name: wystawione_pojazdy_sprzedaz wystawione_pojazdy_sprzedaz_pkey; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.wystawione_pojazdy_sprzedaz
    ADD CONSTRAINT wystawione_pojazdy_sprzedaz_pkey PRIMARY KEY (id_ogloszenia);


--
-- Name: zdjecia_pojazdow zdjecia_pojazdow_pkey; Type: CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.zdjecia_pojazdow
    ADD CONSTRAINT zdjecia_pojazdow_pkey PRIMARY KEY (id_zdjecia);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: uzytkownik
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: klienci klienci_id_konta_foreign; Type: FK CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.klienci
    ADD CONSTRAINT klienci_id_konta_foreign FOREIGN KEY (id_konta) REFERENCES public.konta(id_konta);


--
-- Name: konta konta_id_danych_foreign; Type: FK CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.konta
    ADD CONSTRAINT konta_id_danych_foreign FOREIGN KEY (id_danych) REFERENCES public.dane(id_danych);


--
-- Name: pojazdy pojazdy_id_cechy_pojazdu_foreign; Type: FK CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.pojazdy
    ADD CONSTRAINT pojazdy_id_cechy_pojazdu_foreign FOREIGN KEY (id_cechy_pojazdu) REFERENCES public.cechy_pojazdu(id_cechy_pojazdu);


--
-- Name: pojazdy pojazdy_id_wlasciciela_foreign; Type: FK CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.pojazdy
    ADD CONSTRAINT pojazdy_id_wlasciciela_foreign FOREIGN KEY (id_wlasciciela) REFERENCES public.klienci(id_klienta);


--
-- Name: pracownicy pracownicy_id_konta_foreign; Type: FK CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.pracownicy
    ADD CONSTRAINT pracownicy_id_konta_foreign FOREIGN KEY (id_konta) REFERENCES public.konta(id_konta);


--
-- Name: serwisowane_pojazdy serwisowane_pojazdy_id_pojazdu_foreign; Type: FK CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.serwisowane_pojazdy
    ADD CONSTRAINT serwisowane_pojazdy_id_pojazdu_foreign FOREIGN KEY (id_pojazdu) REFERENCES public.pojazdy(id_pojazdu);


--
-- Name: serwisowane_pojazdy serwisowane_pojazdy_id_pracownika_foreign; Type: FK CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.serwisowane_pojazdy
    ADD CONSTRAINT serwisowane_pojazdy_id_pracownika_foreign FOREIGN KEY (id_pracownika) REFERENCES public.pracownicy(id_pracownika);


--
-- Name: sprzedane_pojazdy sprzedane_pojazdy_id_kupujacego_foreign; Type: FK CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.sprzedane_pojazdy
    ADD CONSTRAINT sprzedane_pojazdy_id_kupujacego_foreign FOREIGN KEY (id_kupujacego) REFERENCES public.klienci(id_klienta);


--
-- Name: sprzedane_pojazdy sprzedane_pojazdy_id_pojazdu_foreign; Type: FK CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.sprzedane_pojazdy
    ADD CONSTRAINT sprzedane_pojazdy_id_pojazdu_foreign FOREIGN KEY (id_pojazdu) REFERENCES public.pojazdy(id_pojazdu);


--
-- Name: wystawione_pojazdy_sprzedaz wystawione_pojazdy_sprzedaz_id_pojazdu_foreign; Type: FK CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.wystawione_pojazdy_sprzedaz
    ADD CONSTRAINT wystawione_pojazdy_sprzedaz_id_pojazdu_foreign FOREIGN KEY (id_pojazdu) REFERENCES public.pojazdy(id_pojazdu);


--
-- Name: zdjecia_pojazdow zdjecia_pojazdow_id_pojazdu_foreign; Type: FK CONSTRAINT; Schema: public; Owner: uzytkownik
--

ALTER TABLE ONLY public.zdjecia_pojazdow
    ADD CONSTRAINT zdjecia_pojazdow_id_pojazdu_foreign FOREIGN KEY (id_pojazdu) REFERENCES public.pojazdy(id_pojazdu) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

