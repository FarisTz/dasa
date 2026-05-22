#  KAFAAT Scholarship Application System with AI-Powered Assistance

## 1. Introduction
The KAFAAT Scholarship Application process was previously carried out manually using Excel forms submitted via email. This approach created several challenges for both applicants and administrators. Applicants were required to download, fill, and email forms, which was time-consuming and prone to errors such as incomplete data, incorrect entries, and missing documents.

Administrators also faced difficulties in managing applications due to lack of a centralized system. Sorting emails, verifying applicant data, and evaluating submissions became inefficient and reduced transparency in the selection process.

To solve these challenges, this project introduces the **KAFAAT Scholarship Application System with AI-Powered Assistance** — a modern web-based platform that digitizes the entire application process.

---

##  2. Problem Statement
The manual scholarship application system:
- Is inefficient and time-consuming  
- Leads to incomplete or incorrect submissions  
- Lacks centralized data management  
- Makes tracking and evaluation difficult  
- Provides no real-time guidance to applicants  

Therefore, there is a need for an **automated, intelligent system** that improves accuracy, usability, and management.

---

##  3. Project Objectives
- Digitize the scholarship application process  
- Provide a structured and user-friendly online application form  
- Reduce errors through guided data entry  
- Enable efficient application management for administrators  
- Integrate AI assistance for user support and validation  

---

##  4. Key Features

###  Applicant Module
- Personal Information Management  
- Next of Kin Information  
- O-Level & A-Level Education Details  
- Document Upload (Certificates, IDs, etc.)  
- Motivation Message Submission  
- Orphan Status Handling (with conditional fields)

### AI-Powered Assistant
- Guides applicants during form filling  
- Reduces errors and incomplete submissions  
- Provides real-time help and suggestions  

### Admin Module
- View and manage all applications  
- Filter and evaluate applicants  
- Verify submitted documents  
- Improve transparency and decision-making  

---

##  5. System Structure

### Main Sections:
1. Personal Information  
2. Contact Information  
3. Identification Details  
4. Additional Information & Documents  
5. Next of Kin  
6. Education (O-Level & A-Level)  
7. Motivation & Special Considerations  

---

## 🗄️ 6. Database Design (Overview)

The system uses a **relational database structure**:

- `applicants` (main table)  
- `next_of_kin` (linked via applicant_id)  
- `education` (stores O-Level & A-Level)  
- `additional_info` (orphan status, motivation, etc.)  

### Relationship:

---

##  7. Technologies Used
- **Backend:** Laravel (PHP Framework)  
- **Frontend:** HTML, CSS, Bootstrap, JavaScript  
- **Database:** MySQL  
- **AI Integration:** (Planned / Optional)  

---

## 8. Benefits of the System
- Faster and more efficient application process  
- Reduced human errors  
- Centralized data management  
- Improved user experience  
- Scalable for future enhancements  

---

## 9. Future Improvements
- Full AI validation of applicant data  
- Automated scoring and ranking system  
- SMS/Email notifications  
- Mobile-friendly optimization  
- Integration with national databases  

---

## 10. System Screenshots
*(Add screenshots here later)*

---

## ⚙️ 11. Installation Guide

```bash
# Clone the repository
git clone https://github.com/FarisTz/dasa.git

# Navigate to project
cd your-repo-name

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Start server
php artisan serve