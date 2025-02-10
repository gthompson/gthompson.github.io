function getQueryParam(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

// Get the 'userID' from the URL
let eventnum = getQueryParam('eventnum');

const csvData = sessionStorage.getItem('csvData');
const rows = JSON.parse(csvData);
const numevents = rows.length;

// Get the radio buttons and image element
const image1Radio = document.getElementById("image1");
const image2Radio = document.getElementById("image2");
const image3Radio = document.getElementById("image3");
const image4Radio = document.getElementById("image4");
const image5Radio = document.getElementById("image5");
const imageDisplay = document.getElementById("imageDisplay");


// Function to update the displayed page and buttons
function updatePage() {
    const row = rows[eventnum-1]
    document.getElementById('header_text').textContent = `Event ${eventnum} of ${numevents}`;
    document.getElementById('event_datetime').textContent = `${row["datetime"]}`;
    document.getElementById('event_slc').textContent = `${row["SLC"]}`;   
    document.getElementById('event_rocket').textContent = `${row["rocket"]}`;   
    document.getElementById('event_mission').textContent = `${row["mission"]}`;       

    // Disable "Previous" button if on the first page
    document.getElementById('prevBtn1').disabled = eventnum <= 1;

    // Disable "Next" button if on the last page
    document.getElementById('nextBtn1').disabled = eventnum >= numevents;

    // Disable "Previous" button if on the first page
    document.getElementById('prevBtn2').disabled = eventnum <= 1;

    // Disable "Next" button if on the last page
    document.getElementById('nextBtn2').disabled = eventnum >= numevents;


    if (image1Radio.checked) {
        set_imgfile('infrasound.png');
    }

    if (image2Radio.checked) {
        set_imgfile('velocity.png');
    }    

    if (image3Radio.checked) {
        set_imgfile('displacement.png');
    }        

    if (image4Radio.checked) {
        set_imgfile('well_calibrated.png');
    } 
    
    if (image5Radio.checked) {
        set_imgfile('water_levels.png');
    }       


}

function set_imgfile(pngbasename) {
    imageDisplay.src = 'EVENTS/' + rows[eventnum-1]['datetime'].replace(' ', 'T') + '/' + pngbasename;
}

function previousPage() {
    if (eventnum > 1) {
        eventnum--;
        updatePage(); // Update the page and buttons
    }
}

function nextPage() {
    if (eventnum < numevents) {
        eventnum++;
        updatePage(); // Update the page and buttons
    }
}

// Initialize the page on load
updatePage();


// Add event listeners for the radio buttons
image1Radio.addEventListener("change", function() {
    if (image1Radio.checked) {
        set_imgfile('infrasound.png');
    }
});

image2Radio.addEventListener("change", function() {
    if (image2Radio.checked) {
        set_imgfile('velocity.png');
    }
});

image3Radio.addEventListener("change", function() {
    if (image3Radio.checked) {
        set_imgfile('displacement.png');
    }
});

image4Radio.addEventListener("change", function() {
    if (image4Radio.checked) {
        set_imgfile('well_calibrated.png');
    }
});

image5Radio.addEventListener("change", function() {
    if (image5Radio.checked) {
        set_imgfile('water_levels.png');
    }
});

