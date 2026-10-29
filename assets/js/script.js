(function() {
    'use strict';

  
    const darkModeToggle = document.getElementById('darkModeToggle');
    const htmlElement = document.documentElement;

    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
        enableDarkMode();
    }

    if (darkModeToggle) {
        darkModeToggle.addEventListener('change', function() {
            if (this.checked) {
                enableDarkMode();
            } else {
                disableDarkMode();
            }
        });
    }

    function enableDarkMode() {
        htmlElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
        if (darkModeToggle) darkModeToggle.checked = true;
    }

    function disableDarkMode() {
        htmlElement.removeAttribute('data-theme');
        localStorage.setItem('theme', 'light');
        if (darkModeToggle) darkModeToggle.checked = false;
    }

    // -----------------------------------------------------------
    // Sidebar Toggle (Mobile)
    // -----------------------------------------------------------
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');

    if (sidebarCollapse) {
        sidebarCollapse.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            content.classList.toggle('margin-collapsed');
        });
    }

    // -----------------------------------------------------------
    // AJAX Delete Student
    // -----------------------------------------------------------
    window.deleteStudentAjax = function(id, name) {
        if (!confirm("Are you sure you want to delete student: " + name + "?")) {
            return;
        }

        // Get CSRF token from session (embedded in page or via meta)
        var csrfToken = document.querySelector('meta[name="csrf-token"]') 
            ? document.querySelector('meta[name="csrf-token"]').content 
            : '';

        $.post('delete-student.php', {
            id: id,
            csrf_token: csrfToken
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.message || 'Failed to delete student.');
            }
        }, 'json').fail(function() {
            alert('Server error. Please try again later.');
        });
    };


    window.searchStudents = function(query) {
        if (query.length < 2) {
            $('#searchResults').html('');
            return;
        }

        $.post('ajax/search-students.php', { search: query }, function(response) {
            var html = '';
            if (response.success && response.students.length > 0) {
                response.students.forEach(function(s) {
                    html += '<div class="border-bottom p-2">';
                    html += '<strong>#' + s.id + '</strong> ' + s.name + ' (' + s.course + ')';
                    html += '</div>';
                });
            } else {
                html = '<div class="p-2 text-muted">No students found.</div>';
            }
            $('#searchResults').html(html);
        }, 'json');
    };

})();