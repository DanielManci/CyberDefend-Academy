<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Topic 4 - Threat Analysis</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<?php include 'header.php'; ?>


<main class="topics-main">
  <div class="main-title">
    <h2>Topic 4: Threat Analysis</h2>
    <p>Explore threats across zones in high-performance computing environments.</p>
  </div>

  <div class="video-preview" style="text-align: center; margin-bottom: 30px;">
    <a href="https://drive.google.com/file/d/1vsfO5UyHNCibSdd6zHoY5zHJFzUVQnM0/view?usp=sharing" target="_blank">
      <img src="images/Threat_Analysis.png" style="width: 30%; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.2);">
    </a>
  </div>

  <section class="section-content" style="max-width: 900px; margin: 0 auto; text-align: left;">
    <h3>Introduction</h3>
    <p>HPC poses unique security and privacy challenges, and collaboration and resource-sharing are integral. For instance, scientific experiments frequently employ unique hardware, software, and configurations that may not be maintained or well-vetted or that present entirely new classes of vulnerabilities that are absent in more traditional environments. HPC can store large amounts of sensitive research data, personally identifiable information (PII), and intellectual property (IP) that need to be safeguarded.</p>
    <p>Finally, HPC data and computation are encumbered with a variety of different security and policy constraints that stem from the fact that HPC systems are often operated as shared resources with different user groups, each of which is required to operate under the goals and constraints set by their organizations. The solutions to protecting data, computation, and workflows must balance these trade-offs.</p>

    <h3>Key HPC Security Characteristics and Use Requirements</h3>
    <ul>
      <li><strong>Tussles between performance and security:</strong> HPC users may consider security to be valuable only to the extent that it does not significantly slow down the HPC system and impede research. Ensuring the usability of security mechanisms with a tolerable performance penalty is therefore critical to adoption by the scientific HPC community.</li>
      <li><strong>Varying security requirements:</strong> Individual platforms, projects, and data may have significantly different security sensitivities and need to follow different security policies. An HPC system may need to enforce multiple security policies simultaneously.</li>
      <li><strong>Limited resources for security tools:</strong> Most HPC systems are designed to devote their resources to maximizing performance rather than acquiring and operating security tools.</li>
      <li><strong>Open-source and research software:</strong> Open-source software is vulnerable to supply chain threats, while HPC software input data may be vulnerable to data supply chain threats. Self-developed software is susceptible to low software quality.</li>
      <li><strong>Granular access control:</strong> Since different research groups may have a need to know for different portions of data, granular access control capabilities are necessary and may need to be dynamically adjusted.</li>
    </ul>

    <h3>Threats to HPC Function Zones</h3>

    <h4>1. Access Zone Threats</h4>
    <p>The access zone provides an interface for external users to access the HPC system and oversees authentication and authorization. It is the only zone directly connected to external networks, making it highly susceptible to threats such as:</p>
    <ul>
      <li>Denial-of-service (DoS) attacks</li>
      <li>Network scanning/sniffing</li>
      <li>Brute force login attempts</li>
      <li>User session hijacking</li>
      <li>Attacker-in-the-middle attacks</li>
    </ul>
    <p>Some nodes, such as those running web servers, are also vulnerable to website defacement, phishing, code injection, and misconfiguration.</p>
    <p>Access control to file systems must be strict, and the use of multi-factor authentication (MFA) is recommended to mitigate unauthorized access risks.</p>

    <h4>2. Management Zone Threats</h4>
    <p>This zone manages the entire HPC system and includes job scheduling, system management, orchestration, and more. It is connected to internal networks and can be a target for:</p>
    <ul>
      <li>Privilege escalation via compromised schedulers</li>
      <li>Delegated authority abuse within distributed systems</li>
    </ul>
    <p>Often, administrator logins follow a two-step process: from the access zone to the management zone. This zone may also be virtualized in the cloud, introducing cloud-specific risks.</p>

    <h4>3. High-Performance Computing Zone Threats</h4>
    <p>The core computing zone is shared by many users. Multi-tenancy threats include:</p>
    <ul>
      <li>Side-channel attacks</li>
      <li>User data or program leakage</li>
      <li>Accidental misconfiguration</li>
      <li>DoS caused by poorly developed user software</li>
    </ul>
    <p>HPC applications typically run in user space, but system calls must execute in kernel space with elevated privileges. Technologies like direct memory access and high-performance interconnects often bypass kernel protections, making monitoring difficult.</p>

    <h3>Conclusion</h3>
    <p>Securing HPC systems is challenging due to their complexity, varying requirements, and shared nature. Security tools are limited, and best practices are still evolving. A zone-based reference architecture helps provide a common language and framework to address HPC security. This structure enables better communication, analysis, and planning across diverse HPC environments.</p>
  </section>

  <div style="text-align: center; margin: 40px 0;">
    <a href="threat_quiz.php" class="btn">Start Quiz</a>
  </div>
</main>

<footer class="footer">
  <a href="privacy.php">Privacy</a>
  <a href="terms.php">Terms</a>
  <a href="contact.php">Contact</a>
  <a href="about.php">About</a>
</footer>

</body>
</html>
