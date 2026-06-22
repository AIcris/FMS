<?php
$require_config   = __DIR__ . '/../config/database.php';
$require_feedback = __DIR__ . '/../src/Feedback.php';

require_once $require_config;
require_once $require_feedback;

$dbInstance = new Database();
$pdo = $dbInstance->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    try {
        $officeName = trim($_POST['office_name'] ?? '');
        
        $stmt = $pdo->prepare("
            SELECT q.qr_id 
            FROM qr_code q
            JOIN department d ON q.department_id = d.department_id
            WHERE d.department_name = ? OR d.department_name LIKE ?
            LIMIT 1
        ");
        $stmt->execute([$officeName, "%" . $officeName . "%"]);
        $matched_qr_id = $stmt->fetchColumn();

        if (!$matched_qr_id) {
            $stmt = $pdo->query("SELECT qr_id FROM qr_code LIMIT 1");
            $matched_qr_id = $stmt->fetchColumn() ?: 1;
        }

        $_POST['qr_id'] = $matched_qr_id;

        $feedbackManager = new Feedback($pdo);
        $success = $feedbackManager->save($_POST);

        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Feedback saved successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to write record to database.']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

$token = $_GET['token'] ?? null;
$detected_qr_id = 1;
$detected_dept_name = "Department of Information Technology";

$require_header = __DIR__ . '/../includes/header.php';
$require_footer = __DIR__ . '/../includes/footer.php';
$require_auth   = __DIR__ . '/../src/auth.php';

require_once $require_auth;
require_once $require_header;

$auth = new Auth($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <main>
    <div class="form-container">
    <div class="header">
        <p class="sub-delivery">Republic of the Philippines</p>
        <h1>Central Luzon State University</h1>
        <p class="location-text">Science City of Muñoz, Nueva Ecija</p>
        <h2>Office of the University President</h2>
        <h1>Citizen's Charter Feedback Form</h1>
        <h3 class="evaluating-header">Evaluating: <?php echo htmlspecialchars($detected_dept_name); ?></h3>
    </div>

    <ul class="progress-steps">
        <li class="step active" id="stepIndicator1">
            <div class="step-number">1</div>
            <span>General Info</span>
        </li>
        <li class="step" id="stepIndicator2">
            <div class="step-number">2</div>
            <span>CC Evaluation</span>
        </li>
        <li class="step" id="stepIndicator3">
            <div class="step-number">3</div>
            <span>SQD Dimension</span>
        </li>
        <li class="step" id="stepIndicator4">
            <div class="step-number">4</div>
            <span>Recom&shy;mendations</span>
        </li>
    </ul>

    <form id="csMeasurementForm">
        <input type="hidden" name="qr_id" value="<?php echo $detected_qr_id; ?>">

        <div class="form-page active" id="page1">
            <div class="section-title">Step 1: General Transaction Profile</div>
            <div class="instruction-box">
                This Client Satisfaction Measurement (CS) tracks the customer experience of government offices. Personal information shared will be kept confidential.
            </div>
            
            <div class="meta-grid">
                <div class="form-group">
                    <label>Client Type:</label>
                    <div class="radio-inline-group">
                        <label><input type="radio" name="client_type" value="Citizen" required> Citizen</label>
                        <label><input type="radio" name="client_type" value="Business"> Business</label>
                        <label><input type="radio" name="client_type" value="Government"> Government</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Client Classification:</label>
                    <div class="radio-inline-group">
                        <label><input type="radio" name="client_classification" value="Student" required> Student</label>
                        <label><input type="radio" name="client_classification" value="Faculty Member"> Faculty Member</label>
                        <label><input type="radio" name="client_classification" value="Non-Academic Staff"> Non-Academic Staff</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Type of Transaction:</label>
                    <div class="radio-inline-group">
                        <label><input type="radio" name="transaction_type" value="Internal" required> Internal</label>
                        <label><input type="radio" name="transaction_type" value="External"> External</label>
                    </div>
                </div>
            </div>

            <div class="meta-grid grid-two-col">
                <div class="form-group">
                    <label>Sex:</label>
                    <div class="radio-inline-group">
                        <label><input type="radio" name="sex" value="Male" required> Male</label>
                        <label><input type="radio" name="sex" value="Female"> Female</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" min="15" max="100" placeholder="e.g., 20" required>
                </div>
            </div>

            <div class="meta-grid grid-two-col">
                <div class="form-group">
                    <label for="region_residence">Region of Residence:</label>
                    <input type="text" id="region_residence" name="region_residence" placeholder="e.g., Region III" required>
                </div>
                <div class="form-group">
                    <label for="service_availed">Service Availed:</label>
                    <input type="text" id="service_availed" name="service_availed" placeholder="e.g., Transcript of Records Request" required>
                </div>
            </div>

            <div class="meta-grid">
                <div class="form-group">
                    <label for="office_name">Name of Office/Department:</label>
                    <input type="text" id="office_name" name="office_name" value="<?php echo htmlspecialchars($detected_dept_name); ?>" readonly class="readonly-input">
                </div>
                <div class="form-group">
                    <label for="provider_name">Name of Service Provider:</label>
                    <input type="text" id="provider_name" name="provider_name" placeholder="Optional">
                </div>
                <div class="form-group">
                    <label for="provider_position">Position of Service Provider:</label>
                    <input type="text" id="provider_position" name="provider_position" placeholder="Optional">
                </div>
            </div>
        </div>

        <div class="form-page" id="page2">
            <div class="section-title">Step 2: Citizen's Charter (CC) Questions</div>
            <div class="instruction-box highlight-box">
                The Citizen’s Charter is an official document that reflects the services of a government agency/office including its requirements, fees, and processing times.
            </div>

            <div class="cc-question">
                <p>CC1. Which of the following best describes your awareness of a CC?</p>
                <div class="cc-options">
                    <label><input type="radio" name="cc1" value="1" required> 1. I know what a CC is and I saw this office’s CC.</label>
                    <label><input type="radio" name="cc1" value="2"> 2. I know what a CC is but I did NOT see this office’s CC.</label>
                    <label><input type="radio" name="cc1" value="3"> 3. I learned of the CC only when I saw this office’s CC.</label>
                    <label><input type="radio" name="cc1" value="4" id="cc1_unaware"> 4. I do not know what a CC is and I did NOT see one in this office. (Skip CC2 and CC3)</label>
                </div>
            </div>

            <div class="cc-question" id="cc2_container">
                <p>CC2. If aware of CC (answered 1-3 in CC1), would you say that the CC on this office was...?</p>
                <div class="cc-options">
                    <label><input type="radio" name="cc2" value="Easy to see" required> Easy to see</label>
                    <label><input type="radio" name="cc2" value="Somewhat easy to see"> Somewhat easy to see</label>
                    <label><input type="radio" name="cc2" value="Difficult to see"> Difficult to see</label>
                    <label><input type="radio" name="cc2" value="Not visible at all"> Not visible at all</label>
                    <label><input type="radio" name="cc2" value="N/A" id="cc2_na"> N/A</label>
                </div>
            </div>

            <div class="cc-question" id="cc3_container">
                <p>CC3. If aware of CC (answered codes 1-3 in CC1), how much did the CC help you in your transaction?</p>
                <div class="cc-options">
                    <label><input type="radio" name="cc3" value="Helped very much" required> Helped very much</label>
                    <label><input type="radio" name="cc3" value="Somewhat helped"> Somewhat helped</label>
                    <label><input type="radio" name="cc3" value="Did not help"> Did not help</label>
                    <label><input type="radio" name="cc3" value="N/A" id="cc3_na"> N/A</label>
                </div>
            </div>
        </div>

        <div class="form-page" id="page3">
            <div class="section-title">Step 3: Service Quality Dimensions (SQD)</div>
            
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2" style="text-align: left; width: 45%;">Evaluation Statements</th>
                            <th><span class="emoji-header">🤬</span>Strongly Disagree<span class="sub-header-text">Awful</span></th>
                            <th><span class="emoji-header">🙁</span>Disagree<span class="sub-header-text">Not very good</span></th>
                            <th><span class="emoji-header">😐</span>Neutral<span class="sub-header-text">Okay</span></th>
                            <th><span class="emoji-header">🙂</span>Agree<span class="sub-header-text">Really good</span></th>
                            <th><span class="emoji-header">😁</span>Strongly Agree<span class="sub-header-text">Fantastic</span></th>
                            <th><span class="emoji-header">🚫</span>Not Applicable<span class="sub-header-text">N/A</span></th>
                        </tr>
                        <tr>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                            <th>N/A</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sqd_questions = [
                            0 => "I am satisfied with the service that I availed.",
                            1 => "I spent a reasonable amount of time for my transaction.",
                            2 => "The office followed the transaction’s requirement from the office or its website.",
                            3 => "The steps (including payment) I needed to do for my transaction were easy and simple.",
                            4 => "I easily found information about my transaction from the office or its website.",
                            5 => "I paid a reasonable amount of fees for my transaction.",
                            6 => "I feel the office was fair to everyone, or “walang palakasan” during my transaction.",
                            7 => "I was treated courteously by the staff, and (if I asked for help) the staff was helpful.",
                            8 => "I got what I needed from the government office, or (if denied) denial of request was sufficiently explained to me."
                        ];
                        foreach ($sqd_questions as $num => $question) {
                            echo '<tr>';
                            echo '<td class="question-cell"><strong>SQD' . $num . '.</strong> ' . $question . '</td>';
                            echo '<td><input type="radio" name="sqd' . $num . '" value="1" required></td>';
                            echo '<td><input type="radio" name="sqd' . $num . '" value="2"></td>';
                            echo '<td><input type="radio" name="sqd' . $num . '" value="3"></td>';
                            echo '<td><input type="radio" name="sqd' . $num . '" value="4"></td>';
                            echo '<td><input type="radio" name="sqd' . $num . '" value="5"></td>';
                            echo '<td><input type="radio" name="sqd' . $num . '" value="N/A"></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-page" id="page4">
            <div class="section-title">Step 4: Overall Institutional Experience</div>
            
            <div class="cc-question">
                <p>Overall, how would you rate your entire educational experience at CLSU?</p>
                <div class="radio-inline-group" style="gap: 20px;">
                    <label><input type="radio" name="educational_experience" value="1" required> 1 (Lowest)</label>
                    <label><input type="radio" name="educational_experience" value="2"> 2</label>
                    <label><input type="radio" name="educational_experience" value="3"> 3</label>
                    <label><input type="radio" name="educational_experience" value="4"> 4</label>
                    <label><input type="radio" name="educational_experience" value="5"> 5 (Highest)</label>
                    <label><input type="radio" name="educational_experience" value="N/A"> N/A</label>
                </div>
            </div>

            <div class="cc-question">
                <p>Have you experienced any form of harassment during the transaction in this office?</p>
                <div class="radio-inline-group" style="margin-bottom: 15px;">
                    <label><input type="radio" name="harassment" value="YES" id="harassment_yes" required> YES</label>
                    <label><input type="radio" name="harassment" value="NO" id="harassment_no"> NO</label>
                </div>
                <div class="form-group textarea-group" id="harassment_details_container" style="display: none;">
                    <label for="harassment_details">If YES, please specify details:</label>
                    <textarea id="harassment_details" name="harassment_details" placeholder="Please provide details here..."></textarea>
                </div>
            </div>

            <div class="cc-question">
                <p>Overall, I would recommend CLSU to my peers.</p>
                <div class="radio-inline-group">
                    <label><input type="radio" name="recommend_clsu" value="YES" required> YES</label>
                    <label><input type="radio" name="recommend_clsu" value="NO"> NO</label>
                </div>
            </div>

            <div class="form-group textarea-group" style="margin-bottom: 20px;">
                <label for="suggestions">Suggestions on how we can further improve our services (Optional):</label>
                <textarea id="suggestions" name="suggestions" placeholder="Your suggestions matter..."></textarea>
            </div>

            <div class="form-group">
                <label for="email_address">Email Address (Optional):</label>
                <input type="email" id="email_address" name="email_address" placeholder="example@email.com">
            </div>
        </div>

        <div class="btn-container">
            <button type="button" class="nav-btn prev-btn" id="prevBtn" style="display: none;">Back</button>
            <button type="button" class="nav-btn next-btn" id="nextBtn">Next</button>
            <button type="submit" class="nav-btn submit-btn" id="submitBtn" style="display: none;">Submit Feedback</button>
        </div>
    </form>

    <div class="footer-note">
        <strong>THANK YOU!</strong><br>
        <span style="font-size: 11px; color:#888;">Adopted from ARTA as per MC 2022-05</span>
    </div>
</div>
</main>
</body>
</html> 

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentPage = 1;
        const totalPages = 4;

        const form = document.getElementById('csMeasurementForm');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');

        const cc1Radios = document.querySelectorAll('input[name="cc1"]');
        const cc2Container = document.getElementById('cc2_container');
        const cc3Container = document.getElementById('cc3_container');
        const cc2Na = document.getElementById('cc2_na');
        const cc3Na = document.getElementById('cc3_na');
        const harassmentRadios = document.querySelectorAll('input[name="harassment"]');
        const harassmentDetailsContainer = document.getElementById('harassment_details_container');
        const harassmentDetailsInput = document.getElementById('harassment_details');

        function showPage(pageNumber) {
            document.querySelectorAll('.form-page').forEach((page, index) => {
                page.classList.toggle('active', index === (pageNumber - 1));
            });

            for (let i = 1; i <= totalPages; i++) {
                const stepIndicator = document.getElementById(`stepIndicator${i}`);
                if (i < pageNumber) {
                    stepIndicator.className = 'step completed';
                } else if (i === pageNumber) {
                    stepIndicator.className = 'step active';
                } else {
                    stepIndicator.className = 'step';
                }
            }

            prevBtn.style.display = pageNumber === 1 ? 'none' : 'inline-block';
            if (pageNumber === totalPages) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'inline-block';
            } else {
                nextBtn.style.display = 'inline-block';
                submitBtn.style.display = 'none';
            }
        }

        function validateCurrentPage() {
            const activePageContainer = document.getElementById(`page${currentPage}`);
            const inputs = activePageContainer.querySelectorAll('input[required], textarea[required]');
            
            for (let input of inputs) {
                if (input.type === 'radio') {
                    const radioGroup = activePageContainer.querySelectorAll(`input[name="${input.name}"]`);
                    const checked = Array.from(radioGroup).some(radio => radio.checked);
                    if (!checked) {
                        alert("Please select an option for all required fields before moving to the next step.");
                        if(radioGroup[0]) radioGroup[0].focus();
                        return false;
                    }
                } else if (!input.value.trim()) {
                    alert("Please fill out all required fields before continuing.");
                    input.focus();
                    return false;
                }
            }
            return true;
        }

        nextBtn.onclick = function() {
            if (validateCurrentPage()) {
                currentPage++;
                showPage(currentPage);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        };

        prevBtn.onclick = function() {
            currentPage--;
            showPage(currentPage);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        cc1Radios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value === '4') {
                    if(cc2Na) cc2Na.checked = true;
                    if(cc3Na) cc3Na.checked = true;
                    if(cc2Container) cc2Container.classList.add('disabled-section');
                    if(cc3Container) cc3Container.classList.add('disabled-section');
                } else {
                    if(cc2Container) cc2Container.classList.remove('disabled-section');
                    if(cc3Container) cc3Container.classList.remove('disabled-section');
                    if (cc2Na && cc2Na.checked) cc2Na.checked = false;
                    if (cc3Na && cc3Na.checked) cc3Na.checked = false;
                }
            });
        });

        harassmentRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.id === 'harassment_yes') {
                    harassmentDetailsContainer.style.display = 'block';
                    harassmentDetailsInput.setAttribute('required', 'true');
                } else {
                    harassmentDetailsContainer.style.display = 'none';
                    harassmentDetailsInput.removeAttribute('required');
                    harassmentDetailsInput.value = '';
                }
            });
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (validateCurrentPage()) {
                const formData = new FormData(this);
                
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';

                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Thank you! Your feedback form sequence has been securely submitted.');
                        form.reset();
                        window.location.reload(); 
                    } else {
                        alert('Error: ' + data.message);
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Submit Feedback';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('A connection problem occurred while sending your form.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Submit Feedback';
                });
            }
        });
    });
</script>
</main>