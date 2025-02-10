let jsonData = null
//const csvFileUrl = 'http://131.247.211.6/usfseismiclab.org/html/rocketcat/launches.csv'; 
let csvFileUrl = 'launches.csv'; 
async function loadCSV() {
    try {
        const response = await fetch(csvFileUrl);  // Load the CSV file from the server
        if (!response.ok) {
            throw new Error('File not found or cannot be read.');
        }
        const csvText = await response.text();  // Get the text content of the CSV file
        //console.log(csvText)
        try {
            const rows = removeEmptyRows(parseCSV(csvText));  // Parse the CSV text into rows
            console.log('got rows')
            try {
                console.log(rows);
                document.getElementById("jsonTable").appendChild(generateTable(rows));
                sessionStorage.setItem('csvData', JSON.stringify(rows));  // Save to sessionStorage
                //console.log(JSON.stringify(rows))
            } catch (error) {
                const errortext = new Text(`Error unknown for ${csvFileUrl}`);
                document.getElementById("jsonTable").appendChild(errortext);                 
            }
        } catch (error) {
            const errortext = new Text(`Error parsing ${csvFileUrl}`);
            document.getElementById("jsonTable").appendChild(errortext);            
        }
    } catch (error) {
        //console.error('Error loading CSV:', error);
        const errortext = new Text(`Error loading ${csvFileUrl}`);
        document.getElementById("jsonTable").appendChild(errortext);
        //alert('Error loading the CSV file.');
    }
}

// Function to parse CSV text into an array of objects
function parseCSV(csvText) {
    //console.log(csvText)
    const rows = csvText.split('\n');  // Split the CSV text into rows
    //console.log(rows)
    const headers = rows[0].split(',');  // The first row contains headers
    //console.log(headers)
    return rows.slice(1).map(row => {
        const values = row.split(',');
        const obj = {};
        //console.log(values)
        if (values.length > 1) {
            console.log(values)
            headers.forEach((header, index) => {
                obj[header.trim()] = values[index].trim();
            });
        }

        return obj;
    });
}

function removeEmptyRows(jsonData) {
    if (Array.isArray(jsonData)) {
      return jsonData.filter(row => {
        if (typeof row === 'object' && row !== null) {
          // Check if the object is empty
          return Object.keys(row).length > 0;
        }
        return true; // Keep non-object rows
      });
    } else if (typeof jsonData === 'object' && jsonData !== null) {
      // If it's an object, recursively remove empty rows from nested arrays
      for (const key in jsonData) {
        if (Array.isArray(jsonData[key])) {
          jsonData[key] = removeEmptyRows(jsonData[key]);
        }
      }
    }
    return jsonData;
}




// Function to generate an HTML table from JSON data
function generateTable(data) {
    // Create a table element
    const table = document.createElement("table");
    table.style.border = "1px solid black";
    table.style.borderCollapse = "collapse";
    table.style.width = "100%";

    // Create the table header row
    const thead = document.createElement("thead");
    const headerRow = document.createElement("tr");

    // Get the keys from the first object (use them as table headers)
    const headers = Object.keys(data[0]);
    colnum = 0;
    headers.forEach(header => {
        const th = document.createElement("th");
        th.style.border = "1px solid black";
        th.style.padding = "8px";
        th.textContent = header; //data[0][colnum];
        headerRow.appendChild(th);
        colnum++;
    });
    const th = document.createElement("th");
    th.style.border = "1px solid black";
    th.style.padding = "8px";
    th.textContent = "Link";
    headerRow.appendChild(th);
    thead.appendChild(headerRow);
    table.appendChild(thead);

    // Create the table body rows
    const tbody = document.createElement("tbody");
    var eventnum=1
    data.forEach(rowData => {
        const row = document.createElement("tr");
        headers.forEach(header => {
            const td = document.createElement("td");
            td.style.border = "1px solid black";
            td.style.padding = "8px";
            td.textContent = rowData[header];
            row.appendChild(td);
        });
        const td = document.createElement("td");
        td.style.border = "1px solid black";
        td.style.padding = "8px";        
        const link = document.createElement("a");
        link.href = `event.html?eventnum=${eventnum}`;
        link.textContent = 'Go'
        td.appendChild(link)
        row.appendChild(td); 
        tbody.appendChild(row);
        eventnum++;
    });
    table.appendChild(tbody);

    return table;
}


// Automatically load CSV and save to sessionStorage when the page loads
//window.onload = loadCSV;
//console.log()

// Function to fetch CSV files from the generated JSON file
function fetchCSVFiles() {
    loadCSV;
    fetch('csv_list.json')  // Fetch the csv_list.json file
        .then(response => response.json())
        .then(files => {
            const selectElement = document.getElementById('csvSelect');
            files.forEach(file => {
                const option = document.createElement('option');
                option.value = file;
                option.textContent = file;
                selectElement.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching CSV files:', error));
}

function updatetable(csvText) {
    document.getElementById("jsonTable").textContent = ""
    try {
        const rows = removeEmptyRows(parseCSV(csvText));  // Parse the CSV text into rows
        console.log('got rows')
        try {
            console.log(rows);
            document.getElementById("jsonTable").appendChild(generateTable(rows));
            sessionStorage.setItem('csvData', JSON.stringify(rows));  // Save to sessionStorage
            //console.log(JSON.stringify(rows))
        } catch (error) {
            const errortext = new Text(`Error unknown for ${csvFileUrl}`);
            document.getElementById("jsonTable").appendChild(errortext);                 
        }
    } catch (error) {
        const errortext = new Text(`Error parsing ${csvFileUrl}`);
        document.getElementById("jsonTable").appendChild(errortext);            
    }

}

// Function to fetch and display content of the selected CSV file
function fetchFileContent(fileName) {
    csvFileUrl = fileName
    var content = ""
    fetch(fileName)
        .then(response => response.text())
        .then(content => {
            updatetable(content)
            //document.getElementById('fileContent').textContent = content;
        })
        .catch(error => console.error('Error fetching file content:', error));
}

// Event listener when a CSV file is selected from the dropdown
document.getElementById('csvSelect').addEventListener('change', function() {
    const selectedFile = this.value;
    if (selectedFile) {
        fetchFileContent(selectedFile);
    }
});

// Load CSV files when the page loads
window.onload = fetchCSVFiles;
