# GMAP Generator

A PHP-based GMAP generator tool that allows users to create, download, and track GMAP files in a zip format. The tool also tracks visitors, generated files, and file downloads. The interface includes a light/dark mode toggle for user preference.

### Features
- **GMAP File Generation**: Users can input the width, height, and file prefix to generate custom GMAP files.
- **Visitor Tracking**: The tool tracks unique visitors using session-based tracking.
- **GMAP and Download Counters**: The system tracks how many GMAP files have been generated and downloaded.
- **Dark/Light Mode Toggle**: Users can switch between light and dark themes, with their preferences saved in localStorage.
- **Client-Side Validation**: Ensures valid input values (e.g., positive integers for width/height) before submitting the form.
- **Progress Indicator**: A loading spinner is shown during GMAP generation to enhance the user experience.
- **Error Handling**: In case of invalid input or missing files, detailed error messages are displayed to guide the user.
- **Security Enhancements**: Input is sanitized and validated to prevent malicious input, ensuring secure file handling.
- **File Deletion**: Once a GMAP file is downloaded, it is automatically deleted from the server to free up space.
  
### Technologies Used
- **PHP** 5.6+ for server-side processing
- **ZipArchive** PHP extension for creating ZIP files
- **HTML/CSS** for the front-end structure and design
- **JavaScript** for client-side validation and dynamic theming (dark/light mode)
  
### Live Demo
You can see a live demo of the GMAP Generator [here](http://yourdomain.com/generatelevels.php). (Down at the moment)

### Requirements

Before setting up the GMAP Generator, ensure the following:
- **PHP 5.6+** (or newer) is installed on your server.
- **ZipArchive** PHP extension is enabled (required for ZIP file creation).

### Installation

Follow these steps to set up the GMAP Generator on your web server:

1. **Clone the Repository**:
    ```bash
    git clone https://github.com/yourrice2k/gmap-generator.git
    cd gmap-generator
    ```

2. **Web Server Configuration**:
    - Ensure that your web server has PHP 5.6 or newer.
    - Ensure the **ZipArchive** extension is enabled on your PHP installation.

3. **Folder Setup**:
    - Create a `maps/` directory in the root of your project where generated GMAP zip files will be stored:
    ```bash
    mkdir maps
    ```

4. **Create Tracking Files**:
    - Create the following files to track visitor and GMAP stats:
      - `visitor_counter.txt`
      - `gmap_counter.txt`
      - `download_counter.txt`

    Ensure that these files are writable by the web server:
    ```bash
    touch visitor_counter.txt gmap_counter.txt download_counter.txt
    chmod 0666 visitor_counter.txt gmap_counter.txt download_counter.txt
    ```

5. **Set Folder Permissions**:
    - Make sure the `maps/` folder is writable so the server can store the generated GMAP files:
    ```bash
    chmod 0777 maps/
    ```

6. **Access the Tool**:
    - Open your web browser and navigate to `http://yourdomain.com/generatelevels.php` to start using the GMAP generator.

### Usage

1. **Fill Out the Form**:
   - Input the **width** and **height** for the GMAP grid.
   - Enter a **prefix** for the GMAP file name.

2. **Generate the GMAP**:
   - Click "Create GMAP" to generate the GMAP file. A loading spinner will appear while the GMAP is being generated.

3. **Download the GMAP**:
   - After the GMAP file is generated, a download link will appear. Click it to download the GMAP in `.zip` format.

4. **Dark/Light Mode**:
   - You can switch between dark and light modes using the "Switch to Dark Mode" button at the top of the page. The tool remembers your preference across sessions.

### Counters and Tracking

- **Visitor Tracking**: The tool tracks each unique visitor using PHP sessions. The total number of visitors is displayed at the bottom of the page.
- **GMAP Generation Counter**: Every time a GMAP is generated, the tool increments the GMAP counter.
- **File Download Counter**: Each time a GMAP is downloaded, the download counter is incremented.

### Error Handling and Validation

- **Client-Side Validation**: The form validates inputs before submission to ensure that valid values are provided.
- **Server-Side Validation**: Input sanitization and validation are applied on the server to prevent invalid or malicious input.
- **Error Messages**: Detailed error messages are displayed to the user if invalid inputs are provided or if there’s an issue with file downloads.

### Security Considerations

- **File Handling**: All file inputs are sanitized to prevent path traversal and other security vulnerabilities.
- **Safe Download Process**: Files are deleted from the server after they are downloaded to free up space and prevent unauthorized access.
  
### Dark/Light Mode

The tool includes a dark/light mode toggle. By default, the tool opens in light mode, but users can switch to dark mode via the "Switch to Dark Mode" button. The chosen mode is saved using `localStorage`, so it will persist across browser sessions.

### Contributing

Contributions are welcome! If you want to contribute to the GMAP Generator project:
1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Commit your changes (`git commit -m "Add new feature"`).
4. Push the branch (`git push origin feature-branch`).
5. Create a pull request.

Feel free to open issues for bug reports or feature requests.

---

### License

This project is licensed under the **MIT License**.
'''

Made by Rice2k for the Graalian Discord Server
