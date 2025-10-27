BEGIN;

CREATE TABLE Authors (
  id BINARY(16) NOT NULL,
  full_name VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL,
  modified_at TIMESTAMP NULL,
  PRIMARY KEY (id)
);

INSERT INTO Authors (id, full_name, created_at, modified_at) VALUES
(
  UNHEX(REPLACE('2cab326a-ab2f-4624-a6d7-2e1855fc5e4e', '-', '')),
  'Terry McDonough',
  '2016-12-12 20:51:00',
  '2016-12-12 20:51:00'
),
(
  UNHEX(REPLACE('95304003-31c8-4aa8-9dd6-d5af7810d621', '-', '')),
  'Jeff Woolnough',
  '2016-12-12 20:53:00',
  '2016-12-12 20:53:00'
),
(
  UNHEX(REPLACE('c4016fc5-01cc-4447-a011-7924dcb659b6', '-', '')),
  'Rob Lieberman',
  '2016-12-12 21:04:00',
  '2016-12-12 21:04:00'
),
(
  UNHEX(REPLACE('9c99c399-4568-4079-960b-dbd333327b32', '-', '')),
  'Bill Johnson',
  '2016-12-12 21:13:00',
  '2016-12-12 21:13:00'
);

COMMIT;