CREATE TABLE task (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL,
    is_completed BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (id),
    INDEX (name)
);

CREATE TABLE user (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL,
    username VARCHAR(128) NOT NULL, 
    email VARCHAR(128) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    api_key VARCHAR(32) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (username),
    UNIQUE (api_key)
);

ALTER TABLE task
    ADD user_id INT NOT NULL,
    ADD INDEX (user_id);

ALTER TABLE task
    ADD FOREIGN KEY (user_id)
    REFERENCES user(id)
    ON DELETE CASCADE ON UPDATE CASCADE;
    
CREATE TABLE refresh_token (
    token_hash VARCHAR(64) NOT NULL,
    expires_at INT UNSIGNED NOT NULL,
    PRIMARY KEY (token_hash),
    INDEX (expires_at)
);