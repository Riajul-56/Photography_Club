-- schema.sql (run once to set up your database)
CREATE TABLE IF NOT EXISTS members (
  member_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  full_name VARCHAR(100) NOT NULL,
  profile_pic VARCHAR(255) DEFAULT 'default.jpg',
  bio TEXT,
  join_date DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS photos (
  photo_id INT AUTO_INCREMENT PRIMARY KEY,
  member_id INT NOT NULL,
  title VARCHAR(100) NOT NULL,
  description TEXT,
  file_path VARCHAR(255) NOT NULL,
  upload_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  category VARCHAR(50),
  CONSTRAINT fk_photos_member FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS events (
  event_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  description TEXT,
  event_date DATE NOT NULL,
  location VARCHAR(255) NOT NULL,
  organizer_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_events_organizer FOREIGN KEY (organizer_id) REFERENCES members(member_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS event_participants (
  event_id INT NOT NULL,
  member_id INT NOT NULL,
  PRIMARY KEY (event_id, member_id),
  CONSTRAINT fk_ep_event FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE,
  CONSTRAINT fk_ep_member FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS event_photos (
  event_id INT NOT NULL,
  photo_id INT NOT NULL,
  PRIMARY KEY (event_id, photo_id),
  CONSTRAINT fk_event_photos_event FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE,
  CONSTRAINT fk_event_photos_photo FOREIGN KEY (photo_id) REFERENCES photos(photo_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;