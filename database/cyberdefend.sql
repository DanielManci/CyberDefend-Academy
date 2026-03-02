-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 02, 2026 at 06:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: cyberdefend
--

-- --------------------------------------------------------

--
-- Table structure for table contact_messages
--

DROP TABLE IF EXISTS contact_messages;
CREATE TABLE IF NOT EXISTS contact_messages (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  email varchar(100) NOT NULL,
  message text NOT NULL,
  submitted_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table feedback
--

DROP TABLE IF EXISTS feedback;
CREATE TABLE IF NOT EXISTS feedback (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  email varchar(100) NOT NULL,
  message text NOT NULL,
  submitted_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table forum_posts
--

DROP TABLE IF EXISTS forum_posts;
CREATE TABLE IF NOT EXISTS forum_posts (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(50) NOT NULL,
  title varchar(255) NOT NULL,
  message text NOT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table forum_posts
--

INSERT INTO forum_posts (id, username, title, message, created_at) VALUES
(11, 'evidence_user', 'Interview', 'How do you prepare for a cybersecurity job interview?', '2025-10-14 12:31:22'),
(12, 'evidence_user', 'Interview preparation for junior cybersecurity roles', 'I have an interview next week for a junior cybersecurity role (SOC/general security). What should I revise in the days before, and what evidence should I bring?', '2025-10-17 11:39:27'),
(13, 'DM', 'asdas', 'dasdasd', '2025-10-20 19:45:22');

-- --------------------------------------------------------

--
-- Table structure for table forum_replies
--

DROP TABLE IF EXISTS forum_replies;
CREATE TABLE IF NOT EXISTS forum_replies (
  id int(11) NOT NULL AUTO_INCREMENT,
  post_id int(11) DEFAULT NULL,
  username varchar(100) DEFAULT NULL,
  reply text DEFAULT NULL,
  created_at datetime DEFAULT current_timestamp(),
  PRIMARY KEY (id),
  KEY post_id (post_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table forum_replies
--

INSERT INTO forum_replies (id, post_id, username, reply, created_at) VALUES
(5, 11, 'DM', 'I suggest revising common vulnerabilities (OWASP Top 10) and doing mock interviews. Practice explaining your projects clearly.', '2025-10-14 13:36:16'),
(6, 12, 'DM', 'Refresh: OWASP Top 10, TLS vs HTTPS, DNS/DNSSEC, common ports, MFA and RBAC.\r\n\r\nDo 3 PortSwigger/TryHackMe labs; keep 2–3 screenshots and 3–4 line notes (vuln → exploit → fix).\r\n\r\nPrepare a 60-second intro and two STAR stories (found a vuln; investigated an alert).\r\n\r\nBring a 1–2 page portfolio: lab notes, a small script/tool, and a simple network diagram.\r\n\r\nBe ready for quick questions: what HTTPS secures, why DNSSEC, how you’d triage a phishing alert.', '2025-10-17 12:40:32');

-- --------------------------------------------------------

--
-- Table structure for table quiz_questions
--

DROP TABLE IF EXISTS quiz_questions;
CREATE TABLE IF NOT EXISTS quiz_questions (
  id int(11) NOT NULL AUTO_INCREMENT,
  topic_id int(11) NOT NULL,
  question_type enum('mcq','fill') NOT NULL DEFAULT 'mcq',
  question text NOT NULL,
  option_a varchar(255) DEFAULT NULL,
  option_b varchar(255) DEFAULT NULL,
  option_c varchar(255) DEFAULT NULL,
  option_d varchar(255) DEFAULT NULL,
  correct_answer varchar(255) DEFAULT NULL,
  explanation text DEFAULT NULL,
  PRIMARY KEY (id),
  KEY topic_id (topic_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table quiz_questions
--

INSERT INTO quiz_questions (id, topic_id, question_type, question, option_a, option_b, option_c, option_d, correct_answer, explanation) VALUES
(1, 1, 'mcq', 'What is the primary goal of HTTPS?', 'Faster web loading', 'Encrypt data between the client and server', 'Allow large file transfers', 'Improve search engine ranking', 'B', 'HTTPS encrypts data exchanged between clients and servers to ensure secure communication.'),
(2, 1, 'mcq', 'Which protocol protects against DNS spoofing?', 'FTPS', 'SMTPS', 'DNSSEC', 'HTTP', 'C', 'DNSSEC (DNS Security Extensions) protect users from DNS spoofing attacks by verifying DNS responses.'),
(3, 1, 'fill', 'Fill in the blank: FTPS encrypts both command and ______ channels.', '', '', '', '', 'data', 'FTPS encrypts both command and data channels for secure file transfers.'),
(4, 2, 'mcq', 'Which type of hacker conducts penetration tests legally?', 'Black Hat', 'White Hat', 'Gray Hat', 'Red Hat', 'White Hat', 'These hackers work legally with permission to test systems.'),
(5, 2, 'mcq', 'Which stage of ethical hacking involves finding system vulnerabilities?', 'Reconnaissance', 'Scanning', 'Gaining Access', 'Maintaining Access', 'Scanning', 'This phase focuses on detecting system weaknesses.'),
(6, 2, 'fill', 'The first phase of ethical hacking is called ______.', '', '', '', '', 'recon', 'This stage involves gathering information before an attack.'),
(7, 3, 'mcq', 'AES encryption typically uses which block size?', '64 bits', '128 bits', '256 bits', '512 bits', 'B', 'AES works with 128-bit blocks, providing strong encryption while balancing performance.'),
(8, 3, 'mcq', 'Which cryptographic method uses two different keys?', 'Symmetric', 'Asymmetric ', 'Hashing', 'XOR cipher', 'B', 'This method relies on a public key to lock the message, and a private key to unlock it.'),
(9, 3, 'fill', 'Cryptography ensures confidentiality, integrity, authentication, and ________.', '', '', '', '', 'non-repudiation', 'This principle ensures that a sender can’t deny sending a message — often backed by digital signatures.'),
(10, 4, 'mcq', 'What does the STRIDE model represent?', 'Encryption methods', 'Types of cybersecurity threats', 'Network protocols', 'Cryptographic keys', 'B', 'STRIDE is a model used to identify and classify common types of cybersecurity threats such as spoofing, tampering, and information disclosure.'),
(11, 4, 'mcq', 'The \'S\' in STRIDE stands for?', 'Spoofing', 'Sniffing', 'Scanning', 'Spoiling', 'A', 'The \'S\' in STRIDE refers to impersonation or identity forgery — commonly known as spoofing.'),
(12, 4, 'fill', 'A threat is a potential cause of an unwanted _______.', '', '', '', '', 'incident', 'A threat becomes serious when it leads to an unexpected event that disrupts systems — known as an incident.'),
(13, 5, 'mcq', 'Which phase of digital forensics involves extracting data of interest from collected evidence?', 'Collection', 'Examination', 'Analysis', 'Reporting', 'B', 'The examination phase involves filtering collected data and extracting relevant evidence.'),
(14, 5, 'mcq', 'What tool ensures data integrity during evidence collection in digital forensics?', 'Write blocker', 'Hard drive duplicator', 'Autopsy', 'FTK Imager', 'A', 'A write blocker is used to prevent any changes to digital evidence during acquisition.'),
(15, 5, 'fill', 'A formal document that tracks evidence handling in forensics is called a ________.', '', '', '', '', 'chain of custody', 'The proper term for the document that tracks how evidence is handled is \'chain of custody\'.');

-- --------------------------------------------------------

--
-- Table structure for table test_questions
--

DROP TABLE IF EXISTS test_questions;
CREATE TABLE IF NOT EXISTS test_questions (
  id int(11) NOT NULL AUTO_INCREMENT,
  topic_id int(11) NOT NULL,
  question_number int(11) NOT NULL,
  question_type varchar(10) NOT NULL,
  question text NOT NULL,
  option_a text DEFAULT NULL,
  option_b text DEFAULT NULL,
  option_c text DEFAULT NULL,
  option_d text DEFAULT NULL,
  correct_answer varchar(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table test_questions
--

INSERT INTO test_questions (id, topic_id, question_number, question_type, question, option_a, option_b, option_c, option_d, correct_answer) VALUES
(1, 1, 0, 'mcq', 'What does HTTPS use to encrypt data transmitted between a web server and a web browser?', 'AES', 'SSL/TLS', 'DES', 'RSA', 'B'),
(2, 1, 0, 'fill', 'FTPS encrypts both ______ and data channels to protect against sniffing attacks.', '', '', '', '', 'command'),
(3, 1, 0, 'mcq', 'Which of the following ports does SMTPS commonly use?', '21 and 22', '443 and 80', '465 and 587', '53 and 110', 'C'),
(4, 1, 0, 'mcq', 'DNSSEC protects against which of the following attacks?', 'DDoS attacks', 'MITM attacks', 'DNS spoofing and cache poisoning', 'Phishing attacks', 'C'),
(5, 1, 0, 'fill', 'Typical network traffic consists of millions of packets per second exchanged among hosts on a ______ and between these hosts and the Internet.', NULL, NULL, NULL, NULL, 'LAN'),
(6, 1, 0, 'mcq', 'Which type of IDS monitors activity on individual devices?', 'NIDS', 'HIDS', 'Signature-based IDS', 'Anomaly-based IDS', 'B'),
(7, 1, 0, 'mcq', 'Which attack involves sending malicious packets disguised as a trusted source?', 'DDoS Attacks', 'IP Spoofing', 'Packet Sniffing', 'MITM Attacks', 'B'),
(8, 1, 0, 'fill', 'OSINT stands for _____ Intelligence.', NULL, NULL, NULL, NULL, 'open-source'),
(9, 1, 0, 'mcq', 'Which statement is true about network intrusions?', 'They only cause a denial of service', 'They aim solely to consume bandwidth', 'They attempt to consume resources, interfere with systems, or gather exploitable knowledge', 'They always originate externally', 'C'),
(10, 1, 0, 'mcq', 'Early Intrusion Detection Systems primarily relied on what detection method?', 'Anomaly detection', 'Heuristic analysis', 'Signature detection', 'Behavioural analysis', 'C'),
(11, 2, 0, 'mcq', 'Ethical hacking is also commonly known as:', 'Black-hat hacking', 'White-hat hacking', 'Grey-hat hacking', 'Red-hat hacking', 'B'),
(12, 2, 0, 'fill', 'The technique that modifies known passwords using specific patterns is called a ______ attack.', NULL, NULL, NULL, NULL, 'rule-based'),
(13, 2, 0, 'mcq', 'What command is used to install John the Ripper on a Linux system?', 'sudo apt install ripper -y', 'sudo apt-get john -y', 'sudo apt install john -y', 'sudo yum install john -y', 'C'),
(14, 2, 0, 'mcq', 'Password spraying is a technique that:', 'Attempts every possible password combination for a single account', 'Tries common passwords across multiple accounts', 'Uses wordlists combined with rules', 'Guesses passwords based on leaked hashes', 'B'),
(15, 2, 0, 'fill', 'Companies often enforce their own ______ policies to prevent weak passwords.', NULL, NULL, NULL, NULL, 'password'),
(16, 2, 0, 'mcq', 'To crack a hashed password using John the Ripper with a wordlist, which command should you use?', 'john --crack hashfile.txt', 'john --wordlist=/usr/share/wordlists/rockyou.txt hashfile.txt', 'john --hash hashfile.txt', 'john --list=rockyou.txt hashfile.txt', 'B'),
(17, 2, 0, 'fill', 'To see cracked passwords in John the Ripper, use the command: john --______ hashfile.txt.', NULL, NULL, NULL, NULL, 'show'),
(18, 2, 0, 'mcq', 'Which of the following helps prevent brute-force attacks?', 'Long, complex passwords', 'Multi-Factor Authentication', 'Limiting login attempts', 'All of the above', 'D'),
(19, 2, 0, 'mcq', 'What command is used to install John the Ripper on a Linux system?', 'sudo apt install ripper -y', 'sudo apt-get john -y', 'sudo apt install john -y', 'sudo yum install john -y', 'C'),
(20, 2, 0, 'mcq', 'What does MFA stand for in cybersecurity?', 'Multiple File Access', 'Multi-Factor Authentication', 'Malicious File Analyzer', 'Managed Firewall Access', 'B'),
(21, 3, 0, 'mcq', 'What is the primary purpose of cryptography?', 'Speeding up internet connections', 'Protecting data from unauthorized access', 'Compressing large files', 'Improving network performance', 'B'),
(22, 3, 0, 'fill', '\"______ ensures that a sender cannot deny sending a particular message.\"', NULL, NULL, NULL, NULL, 'Non-repudiation'),
(23, 3, 0, 'mcq', 'What type of cryptography uses the same key for both encryption and decryption?', 'Asymmetric cryptography', 'Quantum cryptography', 'Symmetric cryptography', 'Elliptic Curve Cryptography (ECC)', 'C'),
(24, 3, 0, 'mcq', 'Quantum cryptography primarily uses what to encrypt data?', 'Public-private key pairs', 'Photon-based encryption', 'Hashing algorithms', 'Caesar Cipher method', 'B'),
(25, 3, 0, 'fill', '\"AES encrypts data in fixed size ______.\"', NULL, NULL, NULL, NULL, 'blocks'),
(26, 3, 0, 'mcq', 'Which cryptographic method is used by blockchain-based cryptocurrencies like Bitcoin?', 'Quantum Cryptography', 'Caesar Cipher', 'RSA encryption', 'Elliptic Curve Cryptography (ECC)', 'D'),
(27, 3, 0, 'mcq', 'Which principle of cryptography ensures data is not altered without authorization?', 'Confidentiality', 'Integrity', 'Non-repudiation', 'Authentication', 'B'),
(28, 3, 0, 'fill', '\"TLS encryption helps prevent ______ attacks during web browsing.\"', NULL, NULL, NULL, NULL, 'MITM'),
(29, 3, 0, 'mcq', 'Which of the following is an example of a hash function?', 'RSA', 'SHA-256', 'ECC', 'AES-256', 'B'),
(30, 3, 0, 'mcq', 'In asymmetric cryptography, what key is used to decrypt messages?', 'Public key', 'Shared key', 'Private key', 'Quantum key', 'C'),
(31, 4, 0, 'mcq', 'What is the primary challenge for security in High-Performance Computing (HPC) systems?', 'Limited physical space', 'Low computing power', 'Diverse and complex hardware and software', 'Simple configurations', 'C'),
(32, 4, 0, 'fill', 'Which HPC zone is directly connected to external networks and most susceptible to external attacks?', NULL, NULL, NULL, NULL, 'Access Zone'),
(33, 4, 0, 'mcq', 'Which security method helps mitigate unauthorized access in the Access Zone?', 'Firewall-only protection', 'Multi-factor authentication (MFA)', 'Unlimited login attempts', 'No user authentication required', 'B'),
(34, 4, 0, 'fill', 'The ______ zone is responsible for managing the entire HPC system.', NULL, NULL, NULL, NULL, 'Management'),
(35, 4, 0, 'mcq', 'What type of threats is associated with the Management Zone due to privileged processes?', 'Denial-of-service attacks', 'Side-channel attacks', 'Privilege escalation', 'Authentication attacks', 'C'),
(36, 4, 0, 'mcq', 'Exploitation of multi-tenancy environments in the HPC Zone can lead to which attack?', 'Website defacement', 'Side-channel attacks', 'Phishing attacks', 'Password guessing attacks', 'B'),
(37, 4, 0, 'fill', 'Granular ______ control capabilities are necessary to restrict database access in HPC environments.', NULL, NULL, NULL, NULL, 'access'),
(38, 4, 0, 'mcq', 'Which technology can pose threats such as container escape in the HPC environment?', 'Virtualization technologies', 'POSIX permissions', 'Multi-factor authentication', 'Physical security controls', 'A'),
(39, 4, 0, 'mcq', 'Which scenario is considered a major threat source in the HPC Zone due to extreme resource consumption?', 'Properly configured systems', 'Authorized system use', 'Accidental misconfiguration', 'Strong security protocols', 'C'),
(40, 4, 0, 'fill', 'HPC systems often struggle with balancing performance and ______.', NULL, NULL, NULL, NULL, 'security'),
(41, 5, 0, 'mcq', 'Which digital forensic phase involves filtering and extracting relevant data from collected evidence?', 'Collection', 'Examination', 'Analysis', 'Reporting', 'B'),
(42, 5, 0, 'fill', 'The two main types of forensic images taken from Windows systems are disk images and ______ images.', NULL, NULL, NULL, NULL, 'memory'),
(43, 5, 0, 'mcq', 'DFIR combines digital forensics with:', 'Cloud storage management', 'Incident response', 'Database analysis', 'Network administration', 'B'),
(44, 5, 0, 'mcq', 'The primary difference between digital forensics and computer forensics is that computer forensics specifically focuses on:', 'Mobile phones only', 'Any digital device', 'Computing devices', 'Network devices', 'C'),
(45, 5, 0, 'fill', 'The forensic image type that captures volatile data from running processes is called a ______ image.', NULL, NULL, NULL, NULL, 'memory'),
(46, 5, 0, 'mcq', 'Which type of digital forensic investigation involves analyzing network traffic logs?', 'Email forensics', 'Cloud forensics', 'Network forensics', 'Database forensics', 'C'),
(47, 5, 0, 'mcq', 'What ensures that collected digital evidence has not been altered?', 'Authorization forms', 'Write blockers', 'GPS logs', 'Keyword searches', 'B'),
(48, 5, 0, 'mcq', 'Digital forensics primarily ensures that digital evidence is:', 'Easily deleted', 'Quickly transmitted', 'Admissible in court', 'Regularly updated', 'C'),
(49, 5, 0, 'fill', 'A ________ is a formal document detailing evidence description, collection details, storage, and access records.', NULL, NULL, NULL, NULL, 'chain of custody'),
(50, 5, 0, 'fill', 'EXIF data provides metadata about digital images, including camera model, GPS location, and __________.', NULL, NULL, NULL, NULL, 'date and time');

-- --------------------------------------------------------

--
-- Table structure for table topics
--

DROP TABLE IF EXISTS topics;
CREATE TABLE IF NOT EXISTS topics (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  short_description varchar(255) DEFAULT NULL,
  description text NOT NULL,
  video_link varchar(512) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table topics
--

INSERT INTO topics (id, title, short_description, description, video_link) VALUES
(1, 'Network Security', 'Explore protocols, threats, and protections in network environments', '<h3 class=\"section-title\">Introduction</h3>\r\n<p>Writing a basic article on network security is something like writing a brief introduction to flying a commercial airliner. Much must be omitted, and an optimistic goal is to enable the reader to appreciate the skills required. The first question to address is: What do we mean by network security? Several possible fields of endeavor come to mind within this broad topic, and each is worthy of a lengthy article. Network security is a subset of computer security, focusing on:</p>\r\n<ul>\r\n  <li>Protecting network traffic from malicious interception.</li>\r\n  <li>Ensuring confidentiality, integrity, and authentication.</li>\r\n  <li>Implementing security policies and configuring network defenses.</li>\r\n  <li>Detecting and preventing cyber intrusions.</li>\r\n</ul>\r\n<p>The practical networking aspects of security include computer intrusion detection, traffic analysis, and network monitoring. This article focuses on these aspects because they principally entail a networking perspective.</p>\r\n\r\n<h3 class=\"section-title\">Network Security Protocols</h3>\r\n<p>A network protocol specifies how two devices, or more precisely processes, communicate with each other. A network protocol is a pre-defined set of rules and processes to determine how data is transmitted between devices, such as end-user devices, networking devices, and servers.</p>\r\n\r\n<ol>\r\n  <li>\r\n    <strong>HTTPS (Hypertext Transfer Protocol Secure):</strong> HTTPS is a client-server protocol responsible for securely sending data between a web server (website) and a web browser (client side). It is an encrypted variant of HTTP, which sends data in an unencrypted format.\r\n    <p><em>How HTTPS Works:</em></p>\r\n    <ul>\r\n      <li>HTTPS uses SSL/TLS to encrypt data.</li>\r\n      <li>Prevents eavesdropping and interception.</li>\r\n      <li>Uses port 443 by default.</li>\r\n    </ul>\r\n  </li>\r\n\r\n  <li>\r\n    <strong>FTPS (File Transfer Protocol Secure):</strong> FTPS is a communication protocol that is a refined and secure version of File Transfer Protocol (FTP).\r\n    <ul>\r\n      <li>FTPS encrypts both command and data channels.</li>\r\n      <li>It prevents sniffing attacks on authentication credentials.</li>\r\n      <li>Implicit FTPS uses port 990, while Explicit FTPS uses port 21.</li>\r\n    </ul>\r\n  </li>\r\n\r\n  <li>\r\n    <strong>SMTPS (Simple Mail Transfer Protocol Secure):</strong> SMTPS is an extension of SMTP, which is used for email communication. SMTPS uses TLS/SSL to provide authentication, integrity, and confidentiality for transferred data.\r\n    <ul>\r\n      <li>SMTP does not encrypt emails by default.</li>\r\n      <li>SMTPS encrypts emails to prevent interception.</li>\r\n      <li>Ports 465 and 587 are used for SMTPS.</li>\r\n    </ul>\r\n  </li>\r\n\r\n  <li>\r\n    <strong>DNSSEC (Domain Name System Security Extensions):</strong> DNSSEC makes it possible to ensure that the DNS response we receive is from the domain owner.\r\n    <ul>\r\n      <li>Ensures the authenticity of DNS records.</li>\r\n      <li>Protects against DNS spoofing and cache poisoning.</li>\r\n      <li>Uses cryptographic signatures to verify domain names.</li>\r\n    </ul>\r\n  </li>\r\n</ol>\r\n\r\n<h3 class=\"section-title\">Common Network Threats</h3>\r\n<p>Typical network traffic consists of millions of packets per second being exchanged among hosts on a LAN and between hosts on the LAN and other hosts on the Internet.</p>\r\n<ul>\r\n  <li>DDoS Attacks — Overwhelming a target with network traffic.</li>\r\n  <li>Packet Sniffing — Intercepting unencrypted data.</li>\r\n  <li>Man-in-the-Middle (MITM) Attacks — Intercepting and modifying traffic.</li>\r\n  <li>IP Spoofing — Sending malicious packets disguised as a trusted source.</li>\r\n  <li>Phishing & Social Engineering — Tricking users into revealing sensitive information.</li>\r\n</ul>\r\n\r\n<h3 class=\"section-title\">Intrusion Detection Systems (IDS)</h3>\r\n<p>Intrusion detection systems (IDSs) use particular collections of analytical techniques to detect attacks, identify their sources, alert network administrators, and possibly mitigate an attack\'s effects.</p>\r\n<ul>\r\n  <li>Signature-based IDS – Detects known attack patterns.</li>\r\n  <li>Anomaly-based IDS – Detects unusual network behavior.</li>\r\n  <li>Network-based IDS (NIDS) – Monitors network traffic for threats.</li>\r\n  <li>Host-based IDS (HIDS) – Monitors activity on individual devices.</li>\r\n</ul>\r\n\r\n<h3 class=\"section-title\">OSINT in Network Security</h3>\r\n<p>OSINT is a double-edged sword. While it helps security teams map the attack surface, it can also be used by adversaries to gather intelligence about an organization.</p>\r\n<ul>\r\n  <li>Phishing Attacks – Extracting employee details from public sources.</li>\r\n  <li>Domain Squatting – Registering similar domain names for malicious purposes.</li>\r\n  <li>Metadata Leaks – Extracting confidential data from public files.</li>\r\n</ul>\r\n\r\n<h3 class=\"section-title\">Conclusion</h3>\r\n<p>Network security involves implementing secure protocols, detecting intrusions, and mitigating cyber threats through a combination of encryption, monitoring, and proactive defense measures.</p>', 'https://drive.google.com/file/d/1sltCVFEc2FfaMTMnbH3UDfQ81B6k2Igr/view'),
(2, 'Ethical Hacking', 'Understand how ethical hackers test systems to strengthen defences.', '<h3 class=\"section-title\">Introduction</h3>\r\n<p>What is Ethical Hacking?</p>\r\n<p>Ethical hacking is the practice of intentionally probing systems, applications, and networks for security vulnerabilities. Unlike malicious hackers, ethical hackers use their skills with permission to strengthen security defences. This process is also known as penetration testing or white hat hacking.</p>\r\n\r\n<p>Ethical hackers use various techniques to simulate real-world cyberattacks and identify weaknesses before malicious attackers can exploit them. One key area of ethical hacking is password attacks, where hackers test the strength of authentication systems.</p>\r\n\r\n<h3 class=\"section-title\">Understanding Password Attacks</h3>\r\n<p>This section introduces the fundamental techniques to perform a successful password attack against various services and scenarios.</p>\r\n\r\n<p>Passwords are one of the most common forms of authentication, but weak or reused passwords can make systems vulnerable to attacks. Cybersecurity professionals must understand different types of password attacks, including:</p>\r\n\r\n<ul>\r\n  <li><strong>Dictionary Attacks:</strong> Using pre-compiled wordlists to guess passwords.</li>\r\n  <li><strong>Brute-Force Attacks:</strong> Trying all possible combinations of characters until the correct password is found.</li>\r\n  <li><strong>Rule-Based Attacks:</strong> Modifying known passwords using specific patterns.</li>\r\n  <li><strong>Password Spraying:</strong> Trying common passwords across multiple accounts to avoid detection.</li>\r\n</ul>\r\n\r\n<p>Passwords with low complexity that are easy to guess are commonly found in publicly disclosed password data breaches. For example: <code>password</code>, <code>123456</code>, <code>111111</code>, and many more.</p>\r\n\r\n<h3 class=\"section-title\">How to Use John the Ripper for Password Cracking</h3>\r\n<p>John the Ripper is a widely used tool for offline password attacks. It can crack password hashes using brute-force, dictionary, and rule-based techniques.</p>\r\n\r\n<ol>\r\n  <li><strong>Installing John the Ripper</strong><br>\r\n  To install on Linux:<br>\r\n  <code>sudo apt install john -y</code></li>\r\n\r\n  <li><strong>Cracking a Hash</strong><br>\r\n  If you have a hashed password, you can try to crack it with a wordlist:<br>\r\n  <code>john --wordlist=/usr/share/wordlists/rockyou.txt hashfile.txt</code><br>\r\n  A dictionary attack uses pre-gathered wordlists to guess passwords.</li>\r\n\r\n  <li><strong>Viewing Cracked Passwords</strong><br>\r\n  After cracking:<br>\r\n  <code>john --show hashfile.txt</code></li>\r\n</ol>\r\n\r\n<p>Brute forcing is a common attack to gain unauthorised access. Attackers guess the victim\'s password by sending common password combinations.</p>\r\n\r\n<h3 class=\"section-title\">Preventing Password Attacks</h3>\r\n<p>To protect against password attacks, organisations should enforce strong password policies. Security measures include:</p>\r\n\r\n<ul>\r\n  <li>Use long and complex passwords (at least 12 characters with uppercase, lowercase, numbers, and symbols).</li>\r\n  <li>Implement Multi-Factor Authentication (MFA) for extra protection.</li>\r\n  <li>Prevent brute-force by limiting login attempts and adding CAPTCHA.</li>\r\n  <li>Check for password leaks using services like Have I Been Pwned.</li>\r\n</ul>\r\n\r\n<p>Many companies enforce internal password policies and complexity requirements to reduce weak password usage and mitigate brute force risks.</p>\r\n\r\n<h3 class=\"section-title\">Conclusion</h3>\r\n<p>Ethical hackers use password attacks to identify weak authentication mechanisms before malicious hackers can exploit them. Tools like John the Ripper and techniques such as brute force, dictionary attacks, and rule-based attacks are essential skills for cybersecurity professionals. By enforcing strong password policies and security measures, organisations can reduce the risk of unauthorised access.</p>', 'https://drive.google.com/file/d/1uT6FLV3l327gaW5o-pknj-WdfzVWIZsh/view'),
(3, 'Cryptography', 'Learn encryption, decryption, and key management fundamentals.', '<h3>Introduction to Cryptography</h3>\r\n\r\n<p><strong>Definition:</strong><br>\r\nCryptography is the practice of developing and using coded algorithms to protect and obscure transmitted information so that it may only be read by those with the permission and ability to decrypt it. It ensures that unauthorized parties cannot access or alter data during transmission.</p>\r\n\r\n<p>The term cryptography is derived from the Greek word “Kryptos”, meaning hidden writing. Historically, cryptography has been used for thousands of years to conceal messages, such as Julius Caesar’s famous Caesar Cipher. Modern cryptography now plays a critical role in cybersecurity, ensuring the protection of sensitive information in online transactions, banking, and secure communications.</p>\r\n\r\n<h3>The Four Core Principles of Cryptography</h3>\r\n<ul>\r\n<li><strong>Confidentiality</strong> – Ensures that only authorized users can access encrypted information.</li>\r\n<li><strong>Integrity</strong> – Protects data from unauthorized modifications, ensuring its authenticity.</li>\r\n<li><strong>Non-repudiation</strong> – Prevents a sender from denying they sent a message.</li>\r\n<li><strong>Authentication</strong> – Confirms the identity of the sender and receiver to prevent impersonation.</li>\r\n</ul>\r\n\r\n<h3>Why Cryptography is Important</h3>\r\n<p>In the digital age, cryptography plays a fundamental role in protecting online data and communications. Every day, we interact with cryptographic systems without realizing it. Some real-world examples include:</p>\r\n<ul>\r\n<li><strong>Passwords</strong> – Cryptography secures password storage using hashing techniques instead of storing plain text passwords.</li>\r\n<li><strong>Cryptocurrency</strong> – Blockchain-based digital currencies such as Bitcoin and Ethereum use cryptography for secure transactions and wallet protection.</li>\r\n<li><strong>Secure Web Browsing (SSL/TLS)</strong> – Websites use TLS encryption to secure the connection between the web browser and server, preventing Man-in-the-Middle (MitM) attacks.</li>\r\n<li><strong>Electronic Signatures</strong> – Used for legal documents to authenticate and verify digital contracts.</li>\r\n<li><strong>Secure Communication</strong> – Apps like WhatsApp and Signal use end-to-end encryption to protect private conversations.</li>\r\n</ul>\r\n\r\n<h3>Types of Cryptography</h3>\r\n<p>There are two main types of cryptography, symmetric encryption and asymmetric encryption, along with hybrid cryptographic models that combine both.</p>\r\n\r\n<h4>3.1 Symmetric Cryptography (Secret Key Cryptography)</h4>\r\n<ul>\r\n<li>Uses one shared key for both encryption and decryption.</li>\r\n<li>Example: The Advanced Encryption Standard (AES) and Data Encryption Standard (DES) use symmetric encryption.</li>\r\n<li><strong>Advantages:</strong> Fast encryption & decryption.</li>\r\n<li><strong>Disadvantages:</strong> If the key is exposed, security is compromised. Key distribution can be difficult.</li>\r\n</ul>\r\n\r\n<p><strong>Example: The Caesar Cipher</strong><br>\r\nPlaintext: HELLO<br>\r\nShift Key: 3<br>\r\nCiphertext: KHOOR</p>\r\n\r\n<h4>3.2 Asymmetric Cryptography (Public Key Cryptography)</h4>\r\n<ul>\r\n<li>Uses a pair of keys: a public key for encryption and a private key for decryption.</li>\r\n<li>Examples: RSA (Rivest-Shamir-Adleman), Diffie-Hellman Key Exchange.</li>\r\n<li><strong>Advantages:</strong> More secure than symmetric encryption. Allows for secure key exchange over the internet.</li>\r\n<li><strong>Disadvantages:</strong> Slower than symmetric encryption. Requires higher computational power.</li>\r\n</ul>\r\n\r\n<h3>Modern Cryptography and Advanced Techniques</h3>\r\n<h4>4.1 Elliptic Curve Cryptography (ECC)</h4>\r\n<ul>\r\n<li>ECC is an advanced public-key encryption method that provides stronger security with smaller key sizes.</li>\r\n<li><strong>Advantages:</strong> More secure than RSA with shorter key lengths. Used in Bitcoin transactions, Apple iMessage, and US government communications.</li>\r\n</ul>\r\n\r\n<h4>4.2 Quantum Cryptography</h4>\r\n<ul>\r\n<li>Uses quantum mechanics to create encryption methods that are immune to traditional cyberattacks.</li>\r\n<li><strong>Concept:</strong> Uses photon-based encryption over fiber-optic cables. Any attempt to eavesdrop changes the quantum state, alerting the receiver.</li>\r\n<li><strong>Challenges:</strong> Requires specialized infrastructure (fiber optic networks). Limited transmission range (~300 miles).</li>\r\n</ul>\r\n\r\n<h3>Cryptographic Algorithms & Key Management</h3>\r\n<h4>5.1 Encryption Algorithms</h4>\r\n<ul>\r\n<li><strong>Block Ciphers:</strong> Encrypt data in fixed-size blocks (e.g., AES-256).</li>\r\n<li><strong>Stream Ciphers:</strong> Encrypt data bit by bit (e.g., RC4).</li>\r\n</ul>\r\n\r\n<h4>5.2 Hashing & Digital Signatures</h4>\r\n<ul>\r\n<li><strong>Hash Functions:</strong> Convert data into a fixed-length hash value for integrity verification. Example: SHA-256.</li>\r\n<li><strong>Digital Signatures:</strong> Used to authenticate messages and prevent forgery.</li>\r\n</ul>\r\n\r\n<h3>Conclusion</h3>\r\n<p>Cryptography is essential in securing digital communication, protecting sensitive data, and ensuring the integrity of online transactions. From password encryption to cryptocurrency and secure web browsing, cryptography is everywhere in modern technology. Future developments like Elliptic Curve Cryptography (ECC) and Quantum Cryptography will shape the next generation of security standards.</p>\r\n', 'https://drive.google.com/file/d/1myatfCRmpE0tVnpSfyVA2AJmMLFeNgbY/view'),
(4, 'Threat Analysis', 'Identify, assess, and mitigate potential cybersecurity threats.', '<h3>Introduction</h3>\r\n<p>HPC poses unique security and privacy challenges, and collaboration and resource-sharing are integral. For instance, scientific experiments frequently employ unique hardware, software, and configurations that may not be maintained or well-vetted or that present entirely new classes of vulnerabilities that are absent in more traditional environments. HPC can store large amounts of sensitive research data, personally identifiable information (PII), and intellectual property (IP) that need to be safeguarded.</p>\r\n\r\n<p>Finally, HPC data and computation are encumbered with a variety of different security and policy constraints that stem from the fact that HPC systems are often operated as shared resources with different user groups, each of which is required to operate under the goals and constraints set by their organizations. The solutions to protecting data, computation, and workflows must balance these trade-offs.</p>\r\n\r\n<h3>Key HPC Security Characteristics and Use Requirements</h3>\r\n<ul>\r\n<li><strong>Tussles between performance and security:</strong> HPC users may consider security to be valuable only to the extent that it does not significantly slow down the HPC system and impede research. Ensuring the usability of security mechanisms with a tolerable performance penalty is therefore critical to adoption by the scientific HPC community.</li>\r\n<li><strong>Varying security requirements:</strong> Individual platforms, projects, and data may have significantly different security sensitivities and need to follow different security policies. An HPC system may need to enforce multiple security policies simultaneously.</li>\r\n<li><strong>Limited resources for security tools:</strong> Most HPC systems are designed to devote their resources to maximizing performance rather than acquiring and operating security tools.</li>\r\n<li><strong>Open-source and research software:</strong> Open-source software is vulnerable to supply chain threats, while HPC software input data may be vulnerable to data supply chain threats. Self-developed software is susceptible to low software quality.</li>\r\n<li><strong>Granular access control:</strong> Since different research groups may have a need to know for different portions of data, granular access control capabilities are necessary and may need to be dynamically adjusted.</li>\r\n</ul>\r\n\r\n<h3>Threats to HPC Function Zones</h3>\r\n\r\n<h4>1. Access Zone Threats</h4>\r\n<p>The access zone provides an interface for external users to access the HPC system and oversees authentication and authorization. It is the only zone directly connected to external networks, making it highly susceptible to threats such as:</p>\r\n<ul>\r\n<li>Denial-of-service (DoS) attacks</li>\r\n<li>Network scanning/sniffing</li>\r\n<li>Brute force login attempts</li>\r\n<li>User session hijacking</li>\r\n<li>Attacker-in-the-middle attacks</li>\r\n</ul>\r\n<p>Some nodes, such as those running web servers, are also vulnerable to website defacement, phishing, code injection, and misconfiguration.</p>\r\n<p>Access control to file systems must be strict, and the use of multi-factor authentication (MFA) is recommended to mitigate unauthorized access risks.</p>\r\n\r\n<h4>2. Management Zone Threats</h4>\r\n<p>This zone manages the entire HPC system and includes job scheduling, system management, orchestration, and more. It is connected to internal networks and can be a target for:</p>\r\n<ul>\r\n<li>Privilege escalation via compromised schedulers</li>\r\n<li>Delegated authority abuse within distributed systems</li>\r\n</ul>\r\n<p>Often, administrator logins follow a two-step process: from the access zone to the management zone. This zone may also be virtualized in the cloud, introducing cloud-specific risks.</p>\r\n\r\n<h4>3. High-Performance Computing Zone Threats</h4>\r\n<p>The core computing zone is shared by many users. Multi-tenancy threats include:</p>\r\n<ul>\r\n<li>Side-channel attacks</li>\r\n<li>User data or program leakage</li>\r\n<li>Accidental misconfiguration</li>\r\n<li>DoS caused by poorly developed user software</li>\r\n</ul>\r\n<p>HPC applications typically run in user space, but system calls must execute in kernel space with elevated privileges. Technologies like direct memory access and high-performance interconnects often bypass kernel protections, making monitoring difficult.</p>\r\n\r\n<h3>Conclusion</h3>\r\n<p>Securing HPC systems is challenging due to their complexity, varying requirements, and shared nature. Security tools are limited, and best practices are still evolving. A zone-based reference architecture helps provide a common language and framework to address HPC security. This structure enables better communication, analysis, and planning across diverse HPC environments.</p>', 'https://drive.google.com/file/d/1vsfO5UyHNCibSdd6zHoY5zHJFzUVQnM0/view'),
(5, 'Digital Forensics', 'Investigate cyber incidents and recover digital evidence securely.', '<h3>Introduction</h3>\r\n<p>Digital forensics is the process of collecting and analysing digital evidence in a way that maintains its integrity and admissibility in court. Digital forensics is a field of forensic science. It is used to investigate cybercrimes but can also help with criminal and civil investigations. For instance, cybersecurity teams may use digital forensics to identify the cybercriminals behind a malware attack, while law enforcement agencies may use it to analyse data from the devices of a murder suspect.</p>\r\n\r\n<p>Digital forensics and computer forensics are often referred to interchangeably. However, digital forensics technically involves gathering evidence from any digital device, whereas computer forensics involves gathering evidence specifically from computing devices, such as computers, tablets, mobile phones, and devices with a CPU.</p>\r\n\r\n<h3>Phases of Digital Forensics</h3>\r\n<p>The digital forensics team has various cases requiring different tools and techniques. However, the National Institute of Standards and Technology (NIST) defines a general process for every case. The NIST works on defining frameworks for different areas of technology, including cyber security, where they introduce the process of digital forensics in four phases.</p>\r\n<ul>\r\n<li><strong>Collection:</strong> Identifying all the devices from which the data can be collected is essential. It is also necessary to ensure the original data is not tampered with while collecting the evidence and to maintain a proper document containing the collected items’ details.</li>\r\n<li><strong>Examination:</strong> The collected data may overwhelm investigators due to its size. This data usually needs to be filtered, and the data of interest needs to be extracted — such as filtering media files from a digital camera by specific date and time.</li>\r\n<li><strong>Analysis:</strong> Investigators now have to analyse the data by correlating it with multiple pieces of evidence to draw conclusions. The analysis depends upon the case scenario and available data.</li>\r\n<li><strong>Reporting:</strong> A detailed report is prepared, containing the investigation’s methodology and findings. It may also include recommendations and executive summaries for various stakeholders.</li>\r\n</ul>\r\n\r\n<h3>Types of Digital Forensics</h3>\r\n<ul>\r\n<li><strong>Computer Forensics:</strong> Investigating computers, the devices most commonly used in crimes.</li>\r\n<li><strong>Mobile Device Forensics:</strong> Extracting evidence like call logs, texts, and GPS data from mobile devices.</li>\r\n<li><strong>Network Forensics:</strong> Investigating network traffic and logs.</li>\r\n<li><strong>Database Forensics:</strong> Identifying intrusion, data modification, or exfiltration from databases.</li>\r\n<li><strong>Cloud Forensics:</strong> Investigating cloud storage and services.</li>\r\n<li><strong>Email Forensics:</strong> Analyzing emails for phishing and fraud investigations.</li>\r\n</ul>\r\n\r\n<h3>Evidence Acquisition Procedures</h3>\r\n<ul>\r\n<li><strong>Proper Authorization:</strong> Evidence without approval may be inadmissible in court.</li>\r\n<li><strong>Chain of Custody:</strong> A formal document detailing all aspects of evidence handling.</li>\r\n<li><strong>Use of Write Blockers:</strong> Prevents alterations to timestamps and original data during collection.</li>\r\n</ul>\r\n\r\n<h3>Windows Forensics</h3>\r\n<p>As part of the data collection phase, forensic images of Windows OS are taken:</p>\r\n<ul>\r\n<li><strong>Disk Image:</strong> Includes non-volatile data such as files, browsing history, documents, etc.</li>\r\n<li><strong>Memory Image:</strong> Contains volatile data like open files, processes, and network connections — lost after shutdown.</li>\r\n</ul>\r\n\r\n<h3>Popular tools</h3>\r\n<ul>\r\n<li><strong>FTK Imager:</strong> Disk imaging and analysis.</li>\r\n<li><strong>Autopsy:</strong> Open-source platform for analyzing disk images and metadata.</li>\r\n<li><strong>DumpIt:</strong> CLI tool for memory imaging.</li>\r\n<li><strong>Volatility:</strong> Open-source memory analysis tool supporting multiple OS types.</li>\r\n</ul>\r\n\r\n<h3>Practical Example of Digital Forensics</h3>\r\n<p>Everything we do on digital devices leaves traces. EXIF metadata in image files can reveal the camera model, GPS location, and capture time. Tools like exiftool help analyse this data. PDF files also contain metadata such as author and creation date, which can be extracted using pdfinfo.</p>\r\n\r\n<h3>Digital Forensics and Incident Response (DFIR)</h3>\r\n<p>DFIR combines digital forensics with incident response. It enables:</p>\r\n<ul>\r\n<li><strong>Forensic Data Collection with Threat Mitigation:</strong> Responders preserve digital evidence while neutralizing threats.</li>\r\n<li><strong>Post-Incident Review:</strong> DFIR teams reconstruct incidents to understand and prevent future attacks.</li>\r\n</ul>\r\n\r\n<h3>Conclusion</h3>\r\n<p>Digital forensics involves collecting, examining, analysing, and reporting digital evidence from various devices. Maintaining a proper chain of custody, ensuring data integrity, and employing suitable forensic tools are vital for legal admissibility.</p>', 'https://drive.google.com/file/d/1abM5l4InBDOXx7gkdcYbVI1cygiLKgoM/view');

-- --------------------------------------------------------

--
-- Table structure for table users
--

DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(50) NOT NULL,
  email varchar(100) NOT NULL,
  password varchar(255) NOT NULL,
  is_admin tinyint(1) DEFAULT 0,
  role varchar(20) NOT NULL DEFAULT 'user',
  PRIMARY KEY (id),
  UNIQUE KEY username (username),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table users
--

INSERT INTO users (id, username, email, password, is_admin, role) VALUES
(10, 'demo_admin', 'demo_admin@cyberdefend.local', '$2y$12$Gk.NtDVhouIMwlqR9hKS3e23Lax2uGXUVBh4o9xoMFcXxZ2Tky7Cu', 1, 'admin'),
(11, 'demo_user1', 'demo_user1@cyberdefend.local', '$2y$12$zYugDwkz6Bl0jjB87Om2P.k2vyNOuZft7Fc7bn63wvqDJvyMvr27y', 0, 'user'),
(13, 'demo_user3', 'demo_user3@cyberdefend.local', '$2y$12$4e/szzeRrvY5Sjw6pKW4K.DigEtcmYMHwj3ieKtKi4TRTCRKPqcoy', 0, 'user'),
(14, 'demo_user2', 'demo_user2@cyberdefend.local', '$2y$12$8MGPyFtREjk/6n9PP0vGAew1iriMXZLz7vfJsVlIZ62botlPM8w4.', 0, 'user');

-- --------------------------------------------------------

--
-- Table structure for table user_scores
--

DROP TABLE IF EXISTS user_scores;
CREATE TABLE IF NOT EXISTS user_scores (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  topic_id int(11) NOT NULL,
  quiz_score int(11) NOT NULL DEFAULT 0,
  test_score int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY unique_user_topic (user_id,topic_id),
  KEY topic_id (topic_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table user_scores
--

INSERT INTO user_scores (id, user_id, topic_id, quiz_score, test_score) VALUES
(111, 11, 1, 3, 10),
(114, 14, 2, 2, 0),
(115, 13, 3, 0, 7);

-- --------------------------------------------------------

--
-- Table structure for table user_test_progress
--

DROP TABLE IF EXISTS user_test_progress;
CREATE TABLE IF NOT EXISTS user_test_progress (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  topic varchar(100) NOT NULL,
  score int(11) NOT NULL,
  total_questions int(11) NOT NULL,
  date_taken datetime DEFAULT current_timestamp(),
  topic_id int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table user_test_progress
--

INSERT INTO user_test_progress (id, user_id, topic, score, total_questions, date_taken, topic_id) VALUES
(75, 11, 'Network Security', 10, 10, '2026-02-28 19:22:16', 1),
(76, 13, 'Cryptography', 7, 10, '2026-02-28 20:13:44', 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table forum_replies
--
ALTER TABLE forum_replies
  ADD CONSTRAINT forum_replies_ibfk_1 FOREIGN KEY (post_id) REFERENCES forum_posts (id) ON DELETE CASCADE;

--
-- Constraints for table quiz_questions
--
ALTER TABLE quiz_questions
  ADD CONSTRAINT quiz_questions_ibfk_1 FOREIGN KEY (topic_id) REFERENCES topics (id) ON DELETE CASCADE;

--
-- Constraints for table user_scores
--
ALTER TABLE user_scores
  ADD CONSTRAINT user_scores_ibfk_1 FOREIGN KEY (user_id) REFERENCES `users` (id),
  ADD CONSTRAINT user_scores_ibfk_2 FOREIGN KEY (topic_id) REFERENCES topics (id);

--
-- Constraints for table user_test_progress
--
ALTER TABLE user_test_progress
  ADD CONSTRAINT user_test_progress_ibfk_1 FOREIGN KEY (user_id) REFERENCES `users` (id);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
