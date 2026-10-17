<?php echo approve_course(); ?>

<div id="bodyright">
    <h3>Unpublished Courses</h3>
    <div id="add">
        <table cellspacing="0">
            <tr>
                <th>Sr No.</th>
                <th></th>
                <th>Course Name</th>
                <th>Number of lectures</th>
                <th>Teacher</th> 
                <th>Approve</th>
                <th>Reject</th>
            </tr>
            <?php echo view_pending(); ?>
        </table>
    </div>
</div>

<!-- Reject Course Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeRejectModal()">&times;</span>
        <h2>Reject Course</h2>
        <form id="rejectForm">
            <input type="hidden" id="rejectCID" name="CID">
            <label>Enter reason for rejection:</label>
            <textarea id="rejectReason" name="rejection_reason" required></textarea>
            <button type="button" onclick="submitRejection()">Confirm</button>
            <button type="button" onclick="closeRejectModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
function openRejectModal(CID) {
    document.getElementById("rejectCID").value = CID;
    document.getElementById("rejectModal").style.display = "block";
}

function closeRejectModal() {
    document.getElementById("rejectModal").style.display = "none";
}

function submitRejection() {
    var CID = document.getElementById("rejectCID").value;
    var reason = document.getElementById("rejectReason").value;

    if (reason.trim() === "") {
        alert("Please enter a rejection reason.");
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "reject_course.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText);
            if (xhr.responseText.trim() === "success") {
                location.reload();
            }
        }
    };

    xhr.send("CID=" + encodeURIComponent(CID) + "&rejection_reason=" + encodeURIComponent(reason));
}

</script>

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: white;
    padding: 20px;
    margin: 15% auto;
    width: 40%;
    border-radius: 10px;
}

.close {
    float: right;
    font-size: 28px;
    cursor: pointer;
}
</style>
