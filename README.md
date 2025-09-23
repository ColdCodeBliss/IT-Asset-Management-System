# IT Asset Management System

A lightweight web-based tool to track, assign, and maintain IT hardware assets. Built with **PHP**, **MySQL**, and **JavaScript**, it’s designed for simplicity, usability, and quick deployment.

---

## ✨ Features

### 📦 Inventory
- Centralized asset table (sortable & searchable).  
- Filters for **Assigned**, **Unassigned**, and **Full Inventory**.  
- Alphabetical sorting by employee name.  

### 👥 Employees
- Add employees with **name, department, and email**.  
- Track asset assignments by employee.  

### 💻 Assets
- Add new assets with details: tag, name, category, purchase info, cost, value, and status.  
- Assign assets via dropdown (shows only unassigned assets).  
- Auto-unassign assets sent to repair.  

### 🛠 Maintenance
- Log issues, resolutions, costs, and technician info.  
- **Maintenance History Log** with CSV/Excel export.  

### 📜 Assignment History
- Full timeline of asset assignments (who had what, and when).  
- Exportable to **CSV/Excel**.  

### 🎵 UX Enhancements
- Success **chime sound** on form submissions.  
- Smooth scroll to active forms.  
- Dark modern UI with toolbar icons.  
- Responsive design with horizontally scrollable toolbar.  

---

## 🛠 Tech Stack
- **Frontend**: HTML5, CSS3, JavaScript  
- **Backend**: PHP 8.2+  
- **Database**: MySQL/MariaDB  
- **Tools**: phpMyAdmin  

---

## 🚀 Setup
1. Import the provided `.sql` file into phpMyAdmin.  
2. Place project files in your web server directory (e.g. `htdocs/ITAssetManagement/`).  
3. Update database credentials in `db.php`.  
4. Access via:  
   ```
   http://localhost/ITAssetManagement/
   ```

---

## ✅ Roadmap
- Role-based access (admin vs. user).  
- Automated reporting.  
- Advanced analytics.  

---

## 📄 License
This project is released under the **MIT License**.  
Feel free to use and adapt it to your organization’s needs.
