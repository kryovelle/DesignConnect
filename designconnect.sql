-- ============================================================
-- DesignConnect Database Schema

-- ============================================================
-- Table: users
-- Stores both clients and designers; role field determines behavior
-- ============================================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('client', 'designer') NOT NULL,
    avatar VARCHAR(255) NULL,
    bio TEXT NULL,
    specialty VARCHAR(100) NULL,
    portfolio_link VARCHAR(255) NULL,
    instagram VARCHAR(150) NULL,
    behance VARCHAR(150) NULL,
    whatsapp VARCHAR(50) NULL,
    notify_new_proposal BOOLEAN DEFAULT TRUE,
    notify_new_request BOOLEAN DEFAULT TRUE,
    created_at DATETIME NOT NULL
);

-- ============================================================
-- Table: requests
-- A design request posted by a client
-- ============================================================
CREATE TABLE requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    budget DECIMAL(10,2) NOT NULL,
    deadline DATE NOT NULL,
    reference_image VARCHAR(255) NULL,
    status ENUM('open', 'in_progress', 'completed') NOT NULL DEFAULT 'open',
    created_at DATETIME NOT NULL,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- Table: proposals
-- A designer's response to a specific request
-- Never publicly visible - only to request owner and submitting designer
-- ============================================================
CREATE TABLE proposals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    request_id INT NOT NULL,
    designer_id INT NOT NULL,
    pitch TEXT NOT NULL,
    portfolio_link VARCHAR(255) NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (request_id) REFERENCES requests(id) ON DELETE CASCADE,
    FOREIGN KEY (designer_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_proposal (request_id, designer_id)
);

-- ============================================================
-- Table: portfolio_samples
-- Sample images attached to designer's profile or to a proposal
-- ============================================================
CREATE TABLE portfolio_samples (
    id INT PRIMARY KEY AUTO_INCREMENT,
    designer_id INT NOT NULL,
    proposal_id INT NULL,
    image_path VARCHAR(255) NOT NULL,
    caption VARCHAR(150) NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (designer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (proposal_id) REFERENCES proposals(id) ON DELETE CASCADE
);

-- ============================================================
-- Table: contact_messages
-- Messages submitted through the public Contact page (standalone)
-- ============================================================
CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME NOT NULL
);


CREATE TABLE admin( id INT AUTO_INCREMENT PRIMARY KEY , username varchar(100),password varchar(200));
CREATE TABLE contact_details(id INT AUTO_INCREMENT PRIMARY KEY,email varchar(100),instagram_url varchar(100),whatsapp varchar(100));


-- ============================================================
-- Sample data (optional, for testing)
-- ============================================================

INSERT INTO contact_details (email,instagram_url,whatsapp) values('designconnect@gmail.com','designconnect_insta','designconnect_whatsapp');

-- Insert a sample client
INSERT INTO users (name, email, password, role, avatar, bio, specialty, portfolio_link, instagram, behance, whatsapp, notify_new_proposal, notify_new_request, created_at)
VALUES ('John Client', 'client@example.com', 'hashed_password_here', 'client', NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, TRUE, NOW());

-- Insert a sample designer
INSERT INTO users (name, email, password, role, avatar, bio, specialty, portfolio_link, instagram, behance, whatsapp, notify_new_proposal, notify_new_request, created_at)
VALUES ('Jane Designer', 'designer@example.com', 'hashed_password_here', 'designer', NULL, 'I love creating beautiful designs.', 'Logo & Branding', 'https://portfolio.example.com', '@janedesigns', '@janedesigns', '+1234567890', TRUE, TRUE, NOW());

-- Insert a sample request
INSERT INTO requests (client_id, title, category, description, budget, deadline, reference_image, status, created_at)
VALUES (1, 'Logo Design for Coffee Shop', 'Logo', 'I need a modern, minimalist logo for my new coffee shop. Prefer warm colors and a vintage feel.', 150.00, '2026-12-01', NULL, 'open', NOW());

-- Insert a sample proposal
INSERT INTO proposals (request_id, designer_id, pitch, portfolio_link, created_at)
VALUES (1, 2, 'I can deliver a modern minimal logo in 3 days. I have experience with coffee shop branding and can show you relevant examples.', 'https://portfolio.example.com/coffee-projects', NOW());

-- Insert a sample portfolio sample
INSERT INTO portfolio_samples (designer_id, proposal_id, image_path, caption, created_at)
VALUES (2, NULL, '/uploads/sample1.jpg', 'Logo design for a boutique hotel', NOW());

-- Insert a sample contact message
INSERT INTO contact_messages (name, email, subject, message, created_at)
VALUES ('Test User', 'test@example.com', 'General', 'This is a test message.', NOW());