document.addEventListener('DOMContentLoaded', () => {
    populateCourseMenu();
});

function populateCourseMenu() {
    const courseMenu = document.getElementById('course-menu');
    courseMenu.innerHTML = '';

    Object.keys(resources).forEach(course => {
        const listItem = document.createElement('li');
        listItem.textContent = course;
        listItem.onclick = () => showResources(course);
        courseMenu.appendChild(listItem);
    });
}

function showResources(course) {
    const resourceContainer = document.getElementById('resource-container');
    resourceContainer.innerHTML = '';
    
    if (resources[course]) {
        resources[course].forEach(resource => {
            const resourceItem = document.createElement('div');
            resourceItem.classList.add('resource-item');
            resourceItem.innerHTML = `<strong>${resource.type}:</strong> <a href="${resource.link}" target="_blank">${resource.link}</a>`;
            resourceContainer.appendChild(resourceItem);
        });
    } else {
        resourceContainer.innerHTML = '<p>No resources available for this course.</p>';
    }
}

function filterCourses() {
    const searchInput = document.getElementById('search-bar').value.toUpperCase();
    const courseMenu = document.getElementById('course-menu');
    const courses = courseMenu.getElementsByTagName('li');

    Array.from(courses).forEach(course => {
        if (course.textContent.toUpperCase().indexOf(searchInput) > -1) {
            course.style.display = '';
        } else {
            course.style.display = 'none';
        }
    });
}
