# Installation Guide

This guide will walk you through setting up the GMAP Generator on your web server.

### Requirements:
- **PHP 5.6+** or newer.
- **ZipArchive** PHP extension enabled.

### Installation Steps:

#### 1. Clone or Download the Repository:
- Clone the repository from GitHub (or download the files):
    ```bash
    git clone https://github.com/yourusername/gmap-generator.git
    cd gmap-generator
    ```
    Or download the ZIP file from GitHub, extract it, and upload the contents to your web server.

#### 2. Create Required Folders:
- Create a directory called `maps/` in the root of your project. This folder will store the generated GMAP zip files:
    ```bash
    mkdir maps
    ```

#### 3. Create Counter Files:
- Create the following text files to track the number of visitors, GMAP files generated, and GMAP files downloaded:
    - `visitor_counter.txt`
    - `gmap_counter.txt`
    - `download_counter.txt`
  
- Ensure that these files have write permissions so the web server can modify them:
    ```bash
    touch visitor_counter.txt gmap_counter.txt download_counter.txt
    chmod 0666 visitor_counter.txt gmap_counter.txt download_counter.txt
    ```

#### 4. Set Folder Permissions:
- Make sure the `maps/` folder is writable by the web server so it can store the generated GMAP files:
    ```bash
    chmod 0777 maps/
    ```

#### 5. Access the Script:
- Open your web browser and navigate to the `generatelevels.php` file. For example:
    ```http
    http://yourdomain.com/generatelevels.php
    ```

#### 6. Generate a GMAP:
1. Enter the **Width** and **Height** of the GMAP grid.
2. Provide a **Prefix** for naming the GMAP levels.
3. Click the "Create GMAP" button to generate a GMAP file.
4. Once generated, a download link will appear for you to download the GMAP as a `.zip` file.

---

### Notes:
- Make sure that the **ZipArchive** PHP extension is enabled on your server for creating zip files.
- The tool uses simple text files (`visitor_counter.txt`, `gmap_counter.txt`, and `download_counter.txt`) to track visitors and the number of files generated or downloaded.

### Security Considerations:
- The GMAP generator ensures file handling security by sanitizing inputs to prevent unauthorized file access.
- The tool automatically deletes GMAP files from the server once they are downloaded to free up space and protect files.

---

### Need Help?
If you run into any issues during the installation process, please [open an issue](https://github.com/yourusername/gmap-generator/issues) on GitHub or contact the repository maintainer.
