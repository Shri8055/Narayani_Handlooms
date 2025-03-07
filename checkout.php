<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('Please log in to continue.');
            window.location.href='login.php';
          </script>";
    exit;
}
$total_price=0;
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Check if "Buy Now" is triggered
if (isset($_SESSION['buy_now']) && !empty($_SESSION['buy_now'])) {
    $checkout_items = $_SESSION['buy_now'];
    foreach ($checkout_items as $item) {
        $total_price += $item['bn_price'] * $item['bn_quantity'];
    }
    unset($_SESSION['buy_now']); // Clear "Buy Now" session after checkout is displayed
} else {
    // Default to cart items if "Buy Now" is not triggered
    $checkout_items = [];
    $user_id = $_SESSION['user_id'];
    $cart_query = "SELECT * FROM cart WHERE user_id = $user_id";
    $cart_result = mysqli_query($conn, $cart_query);
    
    while ($cart_row = mysqli_fetch_assoc($cart_result)) {
        $checkout_items[$cart_row['product_id']] = [
            'bn_name' => $cart_row['product_name'],
            'bn_price' => $cart_row['product_price'],
            'bn_image' => $cart_row['product_image'],
            'bn_quantity' => $cart_row['quantity']
        ];
        $total_price += $cart_row['product_price'] * $cart_row['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Narayani</title>
    <link rel="stylesheet" href="checkout.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body { margin: 20px; }
        .product-container { display: flex; align-items: center; margin-bottom: 20px; }
        .product-container img { width: 100px; height: auto; margin-right: 20px; }
        .product-details { font-size: 18px; }
    </style>
</head>
<body>
<h1>Narayani Handlooms</h1><hr>
<div class="form">
        <form method="POST">
            <label for="contact" class="contact-head">Contact</label>
            <input type="text" id="contact" class="contact" placeholder="Email / Mobile number" required><br>
            <div class="checkbox">
                <input type="checkbox" id="checkbox">
                <label for="checkbox">Email me with news and offers</label><br>
            </div><hr>

            <label for="country" class="country-head">Delivery</label>
            <label for="country">Country</label>
            <select name="country" id="country" required>
                <option value="India">India</option>
                <?php
                $countries = [
                    "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia",
                    "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin",
                    "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi",
                    "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia",
                    "Comoros", "Congo", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica",
                    "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini",
                    "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada",
                    "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia",
                    "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati",
                    "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania",
                    "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania",
                    "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique",
                    "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria",
                    "North Korea", "North Macedonia", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea",
                    "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis",
                    "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia",
                    "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands",
                    "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden",
                    "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga",
                    "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates",
                    "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam",
                    "Yemen", "Zambia", "Zimbabwe"
                ];
                foreach ($countries as $country) {
                    echo '<option value="' . $country . '">' . $country . '</option>';
                }
                ?>
            </select><br>
            <input type="text" name="first-name" class="first-name" placeholder="First name" required>
            <input type="text" name="last-name" class="last-name" placeholder="Last name" required>
            <input type="text" name="company" class="company" placeholder="Company (Optional)">
            <input type="text" name="address" class="address" placeholder="Address" required>
            <input type="text" name="extra-address-details" class="extra-add" placeholder="Apartment, suite, Landmark, etc. (Optional)">
            <input type="text" name="state" class="region" placeholder="State" required>
            <input type="text" name="city" class="region" placeholder="City" required>
            <input type="text" name="pin-code" class="region" placeholder="PIN code" required>
            
            <div class="phone-container">
                <span class="phone-code" id="selected-code">IND +91</span>
                <select id="country-select" onchange="updateCode()">
                <option value="IND +91" selected>India (+91)</option>
                        <option value="AFG +93">Afghanistan (+93)</option>
                        <option value="ALB +355">Albania (+355)</option>
                        <option value="DZA +213">Algeria (+213)</option>
                        <option value="AND +376">Andorra (+376)</option>
                        <option value="AGO +244">Angola (+244)</option>
                        <option value="ATG +1-268">Antigua and Barbuda (+1-268)</option>
                        <option value="ARG +54">Argentina (+54)</option>
                        <option value="ARM +374">Armenia (+374)</option>
                        <option value="AUS +61">Australia (+61)</option>
                        <option value="AUT +43">Austria (+43)</option>
                        <option value="AZE +994">Azerbaijan (+994)</option>
                        <option value="BHS +1-242">Bahamas (+1-242)</option>
                        <option value="BHR +973">Bahrain (+973)</option>
                        <option value="BGD +880">Bangladesh (+880)</option>
                        <option value="BRB +1-246">Barbados (+1-246)</option>
                        <option value="BLR +375">Belarus (+375)</option>
                        <option value="BEL +32">Belgium (+32)</option>
                        <option value="BLZ +501">Belize (+501)</option>
                        <option value="BEN +229">Benin (+229)</option>
                        <option value="BTN +975">Bhutan (+975)</option>
                        <option value="BOL +591">Bolivia (+591)</option>
                        <option value="BIH +387">Bosnia and Herzegovina (+387)</option>
                        <option value="BWA +267">Botswana (+267)</option>
                        <option value="BRA +55">Brazil (+55)</option>
                        <option value="BRN +673">Brunei (+673)</option>
                        <option value="BGR +359">Bulgaria (+359)</option>
                        <option value="BFA +226">Burkina Faso (+226)</option>
                        <option value="BDI +257">Burundi (+257)</option>
                        <option value="CPV +238">Cabo Verde (+238)</option>
                        <option value="KHM +855">Cambodia (+855)</option>
                        <option value="CMR +237">Cameroon (+237)</option>
                        <option value="CAN +1">Canada (+1)</option>
                        <option value="CAF +236">Central African Republic (+236)</option>
                        <option value="TCD +235">Chad (+235)</option>
                        <option value="CHL +56">Chile (+56)</option>
                        <option value="CHN +86">China (+86)</option>
                        <option value="COL +57">Colombia (+57)</option>
                        <option value="COM +269">Comoros (+269)</option>
                        <option value="COG +242">Congo (+242)</option>
                        <option value="CRI +506">Costa Rica (+506)</option>
                        <option value="HRV +385">Croatia (+385)</option>
                        <option value="CUB +53">Cuba (+53)</option>
                        <option value="CYP +357">Cyprus (+357)</option>
                        <option value="CZE +420">Czech Republic (+420)</option>
                        <option value="DNK +45">Denmark (+45)</option>
                        <option value="DJI +253">Djibouti (+253)</option>
                        <option value="DMA +1-767">Dominica (+1-767)</option>
                        <option value="DOM +1-809">Dominican Republic (+1-809)</option>
                        <option value="ECU +593">Ecuador (+593)</option>
                        <option value="EGY +20">Egypt (+20)</option>
                        <option value="SLV +503">El Salvador (+503)</option>
                        <option value="GNQ +240">Equatorial Guinea (+240)</option>
                        <option value="ERI +291">Eritrea (+291)</option>
                        <option value="EST +372">Estonia (+372)</option>
                        <option value="SWZ +268">Eswatini (+268)</option>
                        <option value="ETH +251">Ethiopia (+251)</option>
                        <option value="FJI +679">Fiji (+679)</option>
                        <option value="FIN +358">Finland (+358)</option>
                        <option value="FRA +33">France (+33)</option>
                        <option value="GAB +241">Gabon (+241)</option>
                        <option value="GMB +220">Gambia (+220)</option>
                        <option value="GEO +995">Georgia (+995)</option>
                        <option value="GER +49">Germany (+49)</option>
                        <option value="GHA +233">Ghana (+233)</option>
                        <option value="GRC +30">Greece (+30)</option>
                        <option value="GRD +1-473">Grenada (+1-473)</option>
                        <option value="IND +91" selected>India (+91)</option>
                        <option value="IDN +62">Indonesia (+62)</option>
                        <option value="IRN +98">Iran (+98)</option>
                        <option value="IRQ +964">Iraq (+964)</option>
                        <option value="IRL +353">Ireland (+353)</option>
                        <option value="ISR +972">Israel (+972)</option>
                        <option value="ITA +39">Italy (+39)</option>
                        <option value="JPN +81">Japan (+81)</option>
                        <option value="JOR +962">Jordan (+962)</option>
                        <option value="KEN +254">Kenya (+254)</option>
                        <option value="KOR +82">South Korea (+82)</option>
                        <option value="KWT +965">Kuwait (+965)</option>
                        <option value="MYS +60">Malaysia (+60)</option>
                        <option value="MDV +960">Maldives (+960)</option>
                        <option value="MEX +52">Mexico (+52)</option>
                        <option value="NPL +977">Nepal (+977)</option>
                        <option value="NLD +31">Netherlands (+31)</option>
                        <option value="NZL +64">New Zealand (+64)</option>
                        <option value="NGA +234">Nigeria (+234)</option>
                        <option value="PAK +92">Pakistan (+92)</option>
                        <option value="PHL +63">Philippines (+63)</option>
                        <option value="POL +48">Poland (+48)</option>
                        <option value="PRT +351">Portugal (+351)</option>
                        <option value="QAT +974">Qatar (+974)</option>
                        <option value="ROU +40">Romania (+40)</option>
                        <option value="RUS +7">Russia (+7)</option>
                        <option value="SAU +966">Saudi Arabia (+966)</option>
                        <option value="SGP +65">Singapore (+65)</option>
                        <option value="ESP +34">Spain (+34)</option>
                        <option value="LKA +94">Sri Lanka (+94)</option>
                        <option value="SWE +46">Sweden (+46)</option>
                        <option value="CHE +41">Switzerland (+41)</option>
                        <option value="TWN +886">Taiwan (+886)</option>
                        <option value="THA +66">Thailand (+66)</option>
                        <option value="TUR +90">Turkey (+90)</option>
                        <option value="UKR +380">Ukraine (+380)</option>
                        <option value="UAE +971">United Arab Emirates (+971)</option>
                        <option value="UK +44">United Kingdom (+44)</option>
                        <option value="USA +1">United States (+1)</option>
                        <option value="VNM +84">Vietnam (+84)</option>
                        <option value="YEM +967">Yemen (+967)</option>
                        <option value="ZAF +27">South Africa (+27)</option>
                        <option value="ZMB +260">Zambia (+260)</option>
                        <option value="ZWE +263">Zimbabwe (+263)</option>                        
                </select>
                <input type="text" id="phone-input" placeholder="Phone number, If needed to contact about order" required>
            </div>
            
            <label for="ship-instru">Shipping instruction:</label>
            <input type="text" id="ship-instru" name="ship-instru" placeholder="Shipping Instructions">
            
            <div class="payment">
                <label>Payment</label>
                <p>All transactions are secure and encrypted</p>
            </div>
            <button>Total: ₹<?= number_format($total_price, 2) ?> , Pay Now</button><hr>
            <ul>
            <li><a href="#" onclick="openPopup('refund')">Refund Policy</a></li>
            <li><a href="#" onclick="openPopup('shipping')">Shipping Policy</a></li>
            <li><a href="#" onclick="openPopup('privacy')">Privacy Policy</a></li>
            <li><a href="#" onclick="openPopup('terms')">Terms of Service</a></li>
            <li><a href="#" onclick="openPopup('cancellation')">Cancellation Policy</a></li>
            <li><a href="#" onclick="openPopup('contact')">Contact Information</a></li>
            </ul>
        </form>
        <div id="policyPopup" class="popup">
            <div class="popup-content">
                <span class="close" onclick="closePopup()">&times;</span>
                <h2 id="policyTitle"></h2>
                <p id="policyDescription"></p>
            </div>
        </div>
        <script>
            function openPopup(policy) {
                const policyData = {
                    refund: { title: "Refund Policy", description: "Thank you for purchasing our product ! \n\n Replacements/ Return : Items will be eligible for free replacement, within 7 days of delivery, in an unlikely event of defective or different/wrong item delivered to you. In the rare event that you receive a damaged product, please create a replacement request within 48 hours of order delivery.\n\n Your item must be in the original packaging.\n\n Your items need to have the receipt for proof of purchase.\n\nNo refund only Replacement : Once we received your request, we will inspect and notify you within 24 hours.\n\nIf your request is approved, we will replace the product.\n\nShipping : You will be responsible for paying for your own shipping cost for returning your item if the products reach to you damaged.\n\nShipping cost will be bear by us if different/wrong items is delivered. shipping cost are non refundable.\n\n If you have any queries on how to return the item to us, contact us at 1234567890 or leave us a mail at Narayani2025@gmail.com\n\nThank you !" },
                    shipping: { title: "Shipping Policy", description: "Shipping Information \nWe provide free shipping on orders above ₹1000...\nAvailability : We shipped Pan India.\n\nProcessing time : Allow 3-4 business days processing time for your order to be shipped.\n\nPLEASE NOTE :\n\t◆ It takes 3-4 business days for deliveries pan India, that's excluding our processing time. However, due to Covid restrictions you can expect slight delay in the shipment than usual. We hope for your kind patience and understanding in this matter.\n\n \t◆ All packages are shipped via Delhivery and Bluedart courier service.\n\nOnce your order is shipped, we will notify you via Email or SMS. \n\nTo keep track on your order, whatsapp us at +91 9612608651 or write to us at Runwayindia22@gmail.com.\n\nThank you !" },
                    privacy: { title: "Privacy Policy", description: "Narayani Handlooms Privacy Policy\n\n- Your privacy is important to us. To better protect your privacy we provide this notice explaining our online information practices and the changes you can make about the way your information is collected and used.\n\n- We value your privacy and do not share your data... \n\nWhy we collect this?\n\n- We collect this information to better understand our website visitors, and to monitor and protect the security of the website." },
                    terms: { title: "Terms of Service", description: "This website is operated by Narayani Handlooms. Throughout the site, the terms “we”, “us” and “our” refer to Narayani Handlooms. Narayani Handlooms offers this website, including all information, tools and services available from this site to you, the user, conditioned upon your acceptance of all terms, conditions, policies and notices stated here.\n\nBy visiting our site and/ or purchasing something from us, you engage in our “Service” and agree to be bound by the following terms and conditions (“Terms of Service”, “Terms”), including those additional terms and conditions and policies referenced herein and/or available by hyperlink. These Terms of Service apply to all users of the site, including without limitation users who are browsers, vendors, customers, merchants, and/ or contributors of content.\n\nPlease read these Terms of Service carefully before accessing or using our website. By accessing or using any part of the site, you agree to be bound by these Terms of Service.If you do not agree to all the terms and conditions of this agreement, then you may not access the website or use any services. If these Terms of Service are considered an offer, acceptance is expressly limited to these Terms of Service.\n\nAny new features or tools which are added to the current store shall also be subject to the Terms of Service. You can review the most current version of the Terms of Service at any time on this page. We reserve the right to update, change or replace any part of these Terms of Service by posting updates and/or changes to our website. It is your responsibility to check this page periodically for changes.\n\n Your continued use of or access to the website following the posting of any changes constitutes acceptance of those changes.Our store is hosted on Shopify Inc. They provide us with the online e-commerce platform that allows us to sell our products and services to you." },
                    cancellation: { title: "Cancellation Policy", description: "At Narayani Handlooms, we strive to provide our customers with high-quality handmade bags and excellent service. If you wish to cancel an order, please review our policy below:\n\n◆ Order Cancellation Before Shipment: Customers can cancel their order within 24 hours of placing it. A full refund will be issued to the original payment method.\n\n ◆ Cancellation After Shipment: Once an order has been shipped, it cannot be canceled. However, you may request a return after receiving the item, subject to our Return & Refund Policy.\n\n◆ Customized or Made-to-Order Items: Orders for personalized or custom-made products cannot be canceled once processing has begun.\n\n◆ How to Cancel: To cancel an order, contact our support team at [email/contact details] with your order ID.\n\nNarayani Handlooms reserves the right to modify this policy at any time." },
                    contact: { title: "Contact Information", description: "WhatsApp / Phone: +91 1234567890 / +91 1234567890.\n\nEmail: Narayani2025@gmail.com\n\nFacebook: Narayani_Handlooms\n\nInstagram: @Narayani_Handlooms" },
                };
                document.getElementById("policyTitle").innerText = policyData[policy].title;
                document.getElementById("policyDescription").innerText = policyData[policy].description;
                document.getElementById("policyPopup").style.display = "flex";
            }

            function closePopup() {
                document.getElementById("policyPopup").style.display = "none";
            }
        </script>
        <script>
        function updateCode() {
            var select = document.getElementById("country-select");
            var selectedText = select.options[select.selectedIndex].text;
            document.getElementById("selected-code").textContent = selectedText;
        }
    </script>
    </div>
    <div class="form">
        <div class="cart-items">
            <h1>Cart-items</h1><hr class="cart-items-hr">
            <?php if (empty($checkout_items)): ?>
                <h3>Your cart is empty. <a href="home.php">Go Shopping</a></h3>
            <?php else: ?>
    <table>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>
        <?php foreach ($checkout_items as $item): ?>
        <tr>
            <td><img src="<?php echo $item['bn_image']; ?>" width="50"></td>
            <td><?php echo $item['bn_name']; ?></td>
            <td>₹<?php echo $item['bn_price']; ?></td>
            <td><?php echo $item['bn_quantity']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table><hr class="cart-items-hr">
            <h3>Total: ₹<?= number_format($total_price, 2) ?></h3>
            <?php endif; ?>
        </div>
    </form>
</body>
</html>