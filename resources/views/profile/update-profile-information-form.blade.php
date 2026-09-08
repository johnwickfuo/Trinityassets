<div>
    <form method="POST" action="{{ route('profile.update') }}" id="updateprofileform" class="space-y-6">
        @csrf

        <!-- Profile Information Section -->
        <div class="space-y-6">
            <!-- Full Name -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                <div class="md:col-span-1">
                    <label for="fullName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Full Name
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Your display name on the platform</p>
                </div>
                <div class="md:col-span-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="text"
                               id="fullName"
                               name="name"
                               value="{{ Auth::user()->name }}"
                               required
                               class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm py-4"
                        >
                    </div>
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400 hidden">Please add your full name</p>
                </div>
            </div>

            <!-- Phone -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                <div class="md:col-span-1">
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Phone Number
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Used for account verification</p>
                </div>
                <div class="md:col-span-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="phone" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="text"
                               id="phone"
                               name="phone"
                               value="{{ Auth::user()->phone }}"
                               required
                               class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm py-4"
                        >
                    </div>
                </div>
            </div>

            <!-- Email -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                <div class="md:col-span-1">
                    <label for="emailAddress" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email Address
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Your primary contact email</p>
                </div>
                <div class="md:col-span-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="text"
                               id="emailAddress"
                               name="email"
                               value="{{ Auth::user()->email }}"
                               readonly
                               class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:text-gray-300 sm:text-sm py-4 cursor-not-allowed"
                        >
                    </div>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center">
                            <i data-lucide="info" class="h-3.5 w-3.5 mr-1 text-gray-400"></i>
                            Email address cannot be changed
                        </span>
                    </p>
                </div>
            </div>

            <!-- Country -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                <div class="md:col-span-1">
                    <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Country
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Your current location</p>
                </div>
                <div class="md:col-span-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="globe" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <select
                            name="country"
                            id="country"
                            required
                            class="pl-10 pr-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm py-4 appearance-none"
                        >

                            <option value="{{ Auth::user()->country }}" selected>{{ Auth::user()->country }}</option>

                            <option value="Afganistan">Afghanistan</option>
<option value="Albania">Albania</option>
<option value="Algeria">Algeria</option>
<option value="American Samoa">American Samoa</option>
<option value="Andorra">Andorra</option>
<option value="Angola">Angola</option>
<option value="Anguilla">Anguilla</option>
<option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
<option value="Argentina">Argentina</option>
<option value="Armenia">Armenia</option>
<option value="Aruba">Aruba</option>
<option value="Australia">Australia</option>
<option value="Austria">Austria</option>
<option value="Azerbaijan">Azerbaijan</option>
<option value="Bahamas">Bahamas</option>
<option value="Bahrain">Bahrain</option>
<option value="Bangladesh">Bangladesh</option>
<option value="Barbados">Barbados</option>
<option value="Belarus">Belarus</option>
<option value="Belgium">Belgium</option>
<option value="Belize">Belize</option>
<option value="Benin">Benin</option>
<option value="Bermuda">Bermuda</option>
<option value="Bhutan">Bhutan</option>
<option value="Bolivia">Bolivia</option>
<option value="Bonaire">Bonaire</option>
<option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
<option value="Botswana">Botswana</option>
<option value="Brazil">Brazil</option>
<option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
<option value="Brunei">Brunei</option>
<option value="Bulgaria">Bulgaria</option>
<option value="Burkina Faso">Burkina Faso</option>
<option value="Burundi">Burundi</option>
<option value="Cambodia">Cambodia</option>
<option value="Cameroon">Cameroon</option>
<option value="Canada">Canada</option>
<option value="Canary Islands">Canary Islands</option>
<option value="Cape Verde">Cape Verde</option>
<option value="Cayman Islands">Cayman Islands</option>
<option value="Central African Republic">Central African Republic</option>
<option value="Chad">Chad</option>
<option value="Channel Islands">Channel Islands</option>
<option value="Chile">Chile</option>
<option value="China">China</option>
<option value="Christmas Island">Christmas Island</option>
<option value="Cocos Island">Cocos Island</option>
<option value="Colombia">Colombia</option>
<option value="Comoros">Comoros</option>
<option value="Congo">Congo</option>
<option value="Cook Islands">Cook Islands</option>
<option value="Costa Rica">Costa Rica</option>
<option value="Cote DIvoire">Cote D'Ivoire</option>
<option value="Croatia">Croatia</option>
<option value="Cuba">Cuba</option>
<option value="Curaco">Curacao</option>
<option value="Cyprus">Cyprus</option>
<option value="Czech Republic">Czech Republic</option>
<option value="Denmark">Denmark</option>
<option value="Djibouti">Djibouti</option>
<option value="Dominica">Dominica</option>
<option value="Dominican Republic">Dominican Republic</option>
<option value="East Timor">East Timor</option>
<option value="Ecuador">Ecuador</option>
<option value="Egypt">Egypt</option>
<option value="El Salvador">El Salvador</option>
<option value="Equatorial Guinea">Equatorial Guinea</option>
<option value="Eritrea">Eritrea</option>
<option value="Estonia">Estonia</option>
<option value="Ethiopia">Ethiopia</option>
<option value="Falkland Islands">Falkland Islands</option>
<option value="Faroe Islands">Faroe Islands</option>
<option value="Fiji">Fiji</option>
<option value="Finland">Finland</option>
<option value="France">France</option>
<option value="French Guiana">French Guiana</option>
<option value="French Polynesia">French Polynesia</option>
<option value="French Southern Ter">French Southern Ter</option>
<option value="Gabon">Gabon</option>
<option value="Gambia">Gambia</option>
<option value="Georgia">Georgia</option>
<option value="Germany">Germany</option>
<option value="Ghana">Ghana</option>
<option value="Gibraltar">Gibraltar</option>
<option value="Great Britain">Great Britain</option>
<option value="Greece">Greece</option>
<option value="Greenland">Greenland</option>
<option value="Grenada">Grenada</option>
<option value="Guadeloupe">Guadeloupe</option>
<option value="Guam">Guam</option>
<option value="Guatemala">Guatemala</option>
<option value="Guinea">Guinea</option>
<option value="Guyana">Guyana</option>
<option value="Haiti">Haiti</option>
<option value="Hawaii">Hawaii</option>
<option value="Honduras">Honduras</option>
<option value="Hong Kong">Hong Kong</option>
<option value="Hungary">Hungary</option>
<option value="Iceland">Iceland</option>
<option value="India">India</option>
<option value="Indonesia">Indonesia</option>
<option value="Iran">Iran</option>
<option value="Iraq">Iraq</option>
<option value="Ireland">Ireland</option>
<option value="Isle of Man">Isle of Man</option>
<option value="Israel">Israel</option>
<option value="Italy">Italy</option>
<option value="Jamaica">Jamaica</option>
<option value="Japan">Japan</option>
<option value="Jordan">Jordan</option>
<option value="Kazakhstan">Kazakhstan</option>
<option value="Kenya">Kenya</option>
<option value="Kiribati">Kiribati</option>
<option value="Korea North">Korea North</option>
<option value="Korea Sout">Korea South</option>
<option value="Kuwait">Kuwait</option>
<option value="Kyrgyzstan">Kyrgyzstan</option>
<option value="Laos">Laos</option>
<option value="Latvia">Latvia</option>
<option value="Lebanon">Lebanon</option>
<option value="Lesotho">Lesotho</option>
<option value="Liberia">Liberia</option>
<option value="Libya">Libya</option>
<option value="Liechtenstein">Liechtenstein</option>
<option value="Lithuania">Lithuania</option>
<option value="Luxembourg">Luxembourg</option>
<option value="Macau">Macau</option>
<option value="Macedonia">Macedonia</option>
<option value="Madagascar">Madagascar</option>
<option value="Malaysia">Malaysia</option>
<option value="Malawi">Malawi</option>
<option value="Maldives">Maldives</option>
<option value="Mali">Mali</option>
<option value="Malta">Malta</option>
<option value="Marshall Islands">Marshall Islands</option>
<option value="Martinique">Martinique</option>
<option value="Mauritania">Mauritania</option>
<option value="Mauritius">Mauritius</option>
<option value="Mayotte">Mayotte</option>
<option value="Mexico">Mexico</option>
<option value="Midway Islands">Midway Islands</option>
<option value="Moldova">Moldova</option>
<option value="Monaco">Monaco</option>
<option value="Mongolia">Mongolia</option>
<option value="Montserrat">Montserrat</option>
<option value="Morocco">Morocco</option>
<option value="Mozambique">Mozambique</option>
<option value="Myanmar">Myanmar</option>
<option value="Nambia">Nambia</option>
<option value="Nauru">Nauru</option>
<option value="Nepal">Nepal</option>
<option value="Netherland Antilles">Netherland Antilles</option>
<option value="Netherlands">Netherlands (Holland, Europe)</option>
<option value="Nevis">Nevis</option>
<option value="New Caledonia">New Caledonia</option>
<option value="New Zealand">New Zealand</option>
<option value="Nicaragua">Nicaragua</option>
<option value="Niger">Niger</option>
<option value="Nigeria">Nigeria</option>
<option value="Niue">Niue</option>
<option value="Norfolk Island">Norfolk Island</option>
<option value="Norway">Norway</option>
<option value="Oman">Oman</option>
<option value="Pakistan">Pakistan</option>
<option value="Palau Island">Palau Island</option>
<option value="Palestine">Palestine</option>
<option value="Panama">Panama</option>
<option value="Papua New Guinea">Papua New Guinea</option>
<option value="Paraguay">Paraguay</option>
<option value="Peru">Peru</option>
<option value="Phillipines">Philippines</option>
<option value="Pitcairn Island">Pitcairn Island</option>
<option value="Poland">Poland</option>
<option value="Portugal">Portugal</option>
<option value="Puerto Rico">Puerto Rico</option>
<option value="Qatar">Qatar</option>
<option value="Republic of Montenegro">Republic of Montenegro</option>
<option value="Republic of Serbia">Republic of Serbia</option>
<option value="Reunion">Reunion</option>
<option value="Romania">Romania</option>
<option value="Russia">Russia</option>
<option value="Rwanda">Rwanda</option>
<option value="St Barthelemy">St Barthelemy</option>
<option value="St Eustatius">St Eustatius</option>
<option value="St Helena">St Helena</option>
<option value="St Kitts-Nevis">St Kitts-Nevis</option>
<option value="St Lucia">St Lucia</option>
<option value="St Maarten">St Maarten</option>
<option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option>
<option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines</option>
<option value="Saipan">Saipan</option>
<option value="Samoa">Samoa</option>
<option value="Samoa American">Samoa American</option>
<option value="San Marino">San Marino</option>
<option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option>
<option value="Saudi Arabia">Saudi Arabia</option>
<option value="Senegal">Senegal</option>
<option value="Serbia">Serbia</option>
<option value="Seychelles">Seychelles</option>
<option value="Sierra Leone">Sierra Leone</option>
<option value="Singapore">Singapore</option>
<option value="Slovakia">Slovakia</option>
<option value="Slovenia">Slovenia</option>
<option value="Solomon Islands">Solomon Islands</option>
<option value="Somalia">Somalia</option>
<option value="South Africa">South Africa</option>
<option value="Spain">Spain</option>
<option value="Sri Lanka">Sri Lanka</option>
<option value="Sudan">Sudan</option>
<option value="Suriname">Suriname</option>
<option value="Swaziland">Swaziland</option>
<option value="Sweden">Sweden</option>
<option value="Switzerland">Switzerland</option>
<option value="Syria">Syria</option>
<option value="Tahiti">Tahiti</option>
<option value="Taiwan">Taiwan</option>
<option value="Tajikistan">Tajikistan</option>
<option value="Tanzania">Tanzania</option>
<option value="Thailand">Thailand</option>
<option value="Togo">Togo</option>
<option value="Tokelau">Tokelau</option>
<option value="Tonga">Tonga</option>
<option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
<option value="Tunisia">Tunisia</option>
<option value="Turkey">Turkey</option>
<option value="Turkmenistan">Turkmenistan</option>
<option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
<option value="Tuvalu">Tuvalu</option>
<option value="Uganda">Uganda</option>
<option value="Ukraine">Ukraine</option>
<option value="United Arab Erimates">United Arab Emirates</option>
<option value="United Kingdom">United Kingdom</option>
<option value="United States of America">United States of America</option>
<option value="Uraguay">Uruguay</option>
<option value="Uzbekistan">Uzbekistan</option>
<option value="Vanuatu">Vanuatu</option>
<option value="Vatican City State">Vatican City State</option>
<option value="Venezuela">Venezuela</option>
<option value="Vietnam">Vietnam</option>
<option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
<option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
<option value="Wake Island">Wake Island</option>
<option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
<option value="Yemen">Yemen</option>
<option value="Zaire">Zaire</option>
<option value="Zambia">Zambia</option>
                            <option value="Zimbabwe">Zimbabwe</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <i data-lucide="chevron-down" class="h-5 w-5 text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Currency -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                <div class="md:col-span-1">
                    <label for="currency_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Display Currency</label>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Changes currency labels; it does not recalculate existing balances</p>
                </div>
                <div class="md:col-span-3">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><i data-lucide="coins" class="h-5 w-5 text-gray-400"></i></div>
                        <select name="currency_code" id="currency_code" required class="block w-full appearance-none rounded-xl border-gray-300 py-4 pl-10 pr-10 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                            @foreach(\App\Support\SupportedCurrencies::all() as $code => $currency)
                                <option value="{{ $code }}" {{ Auth::user()->s_currency === $code ? 'selected' : '' }}>{{ $currency['name'] }} ({{ $code }} — {{ $currency['symbol'] }})</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"><i data-lucide="chevron-down" class="h-5 w-5 text-gray-400"></i></div>
                    </div>
                </div>
            </div>

            <!-- Username -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                <div class="md:col-span-1">
                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Username
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Your unique identifier</p>
                </div>
                <div class="md:col-span-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="at-sign" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="text"
                               id="username"
                               name="username"
                               value="{{ Auth::user()->username }}"
                               readonly
                               class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:text-gray-300 sm:text-sm py-4 cursor-not-allowed"
                        >
                    </div>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center">
                            <i data-lucide="lock" class="h-3.5 w-3.5 mr-1 text-gray-400"></i>
                            Username cannot be changed
                        </span>
                    </p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-5 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-end">
                    <button
                        type="submit"
                        id="profile-save-button"
                        class="inline-flex items-center px-6 py-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                    >
                        <span id="profile-save-label">
                            <i data-lucide="save" class="mr-2 h-5 w-5"></i>
                            Save Changes
                        </span>
                        <span id="profile-saving-label" class="hidden">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons if available
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const form = document.getElementById('updateprofileform');
        const button = document.getElementById('profile-save-button');
        const saveLabel = document.getElementById('profile-save-label');
        const savingLabel = document.getElementById('profile-saving-label');

        function setSaving(saving) {
            button.disabled = saving;
            button.classList.toggle('opacity-60', saving);
            button.classList.toggle('cursor-not-allowed', saving);
            saveLabel.classList.toggle('hidden', saving);
            savingLabel.classList.toggle('hidden', !saving);
        }

        function showProfileToast(message, success) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 border-l-4 p-4 rounded-lg shadow-lg transition-all duration-300 z-50 flex items-start max-w-sm ' +
                (success
                    ? 'bg-green-50 dark:bg-green-900/30 border-green-500 text-green-700 dark:text-green-400'
                    : 'bg-red-50 dark:bg-red-900/30 border-red-500 text-red-700 dark:text-red-400');

            const text = document.createElement('p');
            text.className = 'text-sm font-medium pr-4';
            text.textContent = message;
            const close = document.createElement('button');
            close.type = 'button';
            close.className = 'ml-auto text-current font-bold';
            close.setAttribute('aria-label', 'Dismiss');
            close.textContent = '×';
            close.addEventListener('click', function () { toast.remove(); });
            toast.appendChild(text);
            toast.appendChild(close);
            document.body.appendChild(toast);
            setTimeout(function () { toast.remove(); }, 5000);
        }

        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            if (button.disabled) return;

            setSaving(true);
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new FormData(form)
                });
                const payload = await response.json().catch(function () { return {}; });

                if (!response.ok) {
                    const validationErrors = payload.errors ? Object.values(payload.errors).flat() : [];
                    throw new Error(validationErrors[0] || payload.message || 'Failed to update profile. Please try again.');
                }

                showProfileToast(payload.success || 'Profile information updated successfully.', true);
            } catch (error) {
                showProfileToast(error.message || 'Failed to update profile. Please try again.', false);
            } finally {
                setSaving(false);
            }
        });
    });
</script>
