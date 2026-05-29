-- UnityFund PostgreSQL Schema
-- Run with: psql -U unityfund -d unityfund -f schema.sql

BEGIN;

-- ============================================================
-- managed_user_accounts
-- ============================================================
CREATE TABLE IF NOT EXISTS managed_user_accounts (
    id              SERIAL PRIMARY KEY,
    username        VARCHAR(100) NOT NULL UNIQUE,
    email           VARCHAR(255) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    role            VARCHAR(50)  NOT NULL CHECK (role IN ('user_admin','fundraiser','donee','platform_manager')),
    permission      VARCHAR(50)  NOT NULL DEFAULT 'standard',
    status          VARCHAR(20)  NOT NULL DEFAULT 'Active' CHECK (status IN ('Active','Suspended')),
    created_at      TIMESTAMP    NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_accounts_username ON managed_user_accounts(username);
CREATE INDEX idx_accounts_role     ON managed_user_accounts(role);
CREATE INDEX idx_accounts_status   ON managed_user_accounts(status);

-- ============================================================
-- user_profiles
-- ============================================================
CREATE TABLE IF NOT EXISTS user_profiles (
    id              SERIAL PRIMARY KEY,
    account_id      INTEGER       NOT NULL UNIQUE REFERENCES managed_user_accounts(id) ON DELETE CASCADE,
    full_name       VARCHAR(200)  NOT NULL,
    phone           VARCHAR(50),
    address         TEXT,
    status          VARCHAR(20)   NOT NULL DEFAULT 'Active' CHECK (status IN ('Active','Suspended')),
    created_at      TIMESTAMP     NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_profiles_account ON user_profiles(account_id);
CREATE INDEX idx_profiles_name    ON user_profiles(full_name);

-- ============================================================
-- fra_categories
-- ============================================================
CREATE TABLE IF NOT EXISTS fra_categories (
    id              SERIAL PRIMARY KEY,
    category_name   VARCHAR(150)  NOT NULL UNIQUE,
    description     TEXT,
    status          VARCHAR(20)   NOT NULL DEFAULT 'Active' CHECK (status IN ('Active','Suspended')),
    created_at      TIMESTAMP     NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_categories_name   ON fra_categories(category_name);
CREATE INDEX idx_categories_status ON fra_categories(status);

-- ============================================================
-- fundraising_activities
-- ============================================================
CREATE TABLE IF NOT EXISTS fundraising_activities (
    id              SERIAL PRIMARY KEY,
    fundraiser_id   INTEGER       NOT NULL REFERENCES managed_user_accounts(id),
    title           VARCHAR(255)  NOT NULL,
    description     TEXT,
    goal_amount     NUMERIC(12,2) NOT NULL DEFAULT 0,
    current_amount  NUMERIC(12,2) NOT NULL DEFAULT 0,
    category        VARCHAR(150)  NOT NULL,
    status          VARCHAR(20)   NOT NULL DEFAULT 'Active' CHECK (status IN ('Active','Disabled','Completed')),
    start_date      DATE          NOT NULL DEFAULT CURRENT_DATE,
    end_date        DATE,
    view_count      INTEGER       NOT NULL DEFAULT 0,
    shortlist_count INTEGER       NOT NULL DEFAULT 0,
    created_at      TIMESTAMP     NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_fra_fundraiser ON fundraising_activities(fundraiser_id);
CREATE INDEX idx_fra_category   ON fundraising_activities(category);
CREATE INDEX idx_fra_status     ON fundraising_activities(status);
CREATE INDEX idx_fra_title      ON fundraising_activities(title);

-- ============================================================
-- favorites
-- ============================================================
CREATE TABLE IF NOT EXISTS favorites (
    id              SERIAL PRIMARY KEY,
    donee_id        INTEGER NOT NULL REFERENCES managed_user_accounts(id) ON DELETE CASCADE,
    activity_id     INTEGER NOT NULL REFERENCES fundraising_activities(id) ON DELETE CASCADE,
    created_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    UNIQUE(donee_id, activity_id)
);

CREATE INDEX idx_favorites_donee    ON favorites(donee_id);
CREATE INDEX idx_favorites_activity ON favorites(activity_id);

-- ============================================================
-- donations
-- ============================================================
CREATE TABLE IF NOT EXISTS donations (
    id              SERIAL PRIMARY KEY,
    donee_id        INTEGER       NOT NULL REFERENCES managed_user_accounts(id) ON DELETE CASCADE,
    activity_id     INTEGER       NOT NULL REFERENCES fundraising_activities(id),
    amount          NUMERIC(12,2) NOT NULL DEFAULT 0,
    donation_date   TIMESTAMP     NOT NULL DEFAULT NOW(),
    status          VARCHAR(20)   NOT NULL DEFAULT 'Completed' CHECK (status IN ('Completed','Pending','Failed'))
);

CREATE INDEX idx_donations_donee    ON donations(donee_id);
CREATE INDEX idx_donations_activity ON donations(activity_id);
CREATE INDEX idx_donations_date     ON donations(donation_date);

-- ============================================================
-- daily_reports
-- ============================================================
CREATE TABLE IF NOT EXISTS daily_reports (
    id                      SERIAL PRIMARY KEY,
    report_date             DATE NOT NULL UNIQUE,
    total_funds_raised      NUMERIC(14,2) NOT NULL DEFAULT 0,
    donation_count          INTEGER       NOT NULL DEFAULT 0,
    transaction_count       INTEGER       NOT NULL DEFAULT 0,
    completed_activities    INTEGER       NOT NULL DEFAULT 0,
    average_donation        NUMERIC(12,2) NOT NULL DEFAULT 0,
    created_at              TIMESTAMP     NOT NULL DEFAULT NOW()
);

-- ============================================================
-- weekly_reports
-- ============================================================
CREATE TABLE IF NOT EXISTS weekly_reports (
    id                      SERIAL PRIMARY KEY,
    week_start              DATE NOT NULL UNIQUE,
    total_funds_raised      NUMERIC(14,2) NOT NULL DEFAULT 0,
    donation_count          INTEGER       NOT NULL DEFAULT 0,
    transaction_count       INTEGER       NOT NULL DEFAULT 0,
    completed_activities    INTEGER       NOT NULL DEFAULT 0,
    average_donation        NUMERIC(12,2) NOT NULL DEFAULT 0,
    created_at              TIMESTAMP     NOT NULL DEFAULT NOW()
);

-- ============================================================
-- monthly_reports
-- ============================================================
CREATE TABLE IF NOT EXISTS monthly_reports (
    id                      SERIAL PRIMARY KEY,
    month_start             DATE NOT NULL UNIQUE,
    total_funds_raised      NUMERIC(14,2) NOT NULL DEFAULT 0,
    donation_count          INTEGER       NOT NULL DEFAULT 0,
    transaction_count       INTEGER       NOT NULL DEFAULT 0,
    completed_activities    INTEGER       NOT NULL DEFAULT 0,
    average_donation        NUMERIC(12,2) NOT NULL DEFAULT 0,
    created_at              TIMESTAMP     NOT NULL DEFAULT NOW()
);

COMMIT;
