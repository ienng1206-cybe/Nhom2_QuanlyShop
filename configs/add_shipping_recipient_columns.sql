ALTER TABLE shipping
ADD COLUMN recipient_name VARCHAR(120) NULL AFTER phone,
ADD COLUMN recipient_email VARCHAR(150) NULL AFTER recipient_name;
