document.getElementById("searchForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const query = document.getElementById("searchInput").value;
    alert("You searched for: " + query);
});
