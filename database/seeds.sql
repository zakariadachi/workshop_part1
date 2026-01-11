-- Insert test team
INSERT INTO teams (name, description) VALUES 
('Alpha Team', 'Frontend development team');

-- Insert test members
INSERT INTO team_members (username, email, password_hash, role, team_id) VALUES 
('alice_dev', 'alice@company.com', '$2y$10$YourHashedPasswordHere', 'developer', 1),
('bob_tester', 'bob@company.com', '$2y$10$YourHashedPasswordHere', 'tester', 1),
('charlie_manager', 'charlie@company.com', '$2y$10$YourHashedPasswordHere', 'manager', 1),
('diana_admin', 'diana@company.com', '$2y$10$YourHashedPasswordHere', 'admin', 1);

-- Insert test project
INSERT INTO projects (name, description, team_id, status) VALUES 
('Website Redesign', 'Complete website overhaul', 1, 'active');
