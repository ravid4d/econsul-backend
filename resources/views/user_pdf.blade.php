<!DOCTYPE html>
<html>

<head>
    <title>User Data PDF</title>
    <style>
        .h6 {
            font-size: 22px;
            line-height: 30px;
            font-weight: 400;
            font-family: "Open Sans", sans-serif;
        }

        .mainTopHeading {
            font-size: 28px;
            line-height: 30px;
            font-weight: 400;
            margin: 0 0 15px 0;
            font-family: "Open Sans", sans-serif;
        }

        .confirmInformationBlock {
            max-width: 100%;
        }

        .showFormFields .fullWithCol {
            display: flex;
            flex-direction: column;
            / gap: 24px;/ gap: 10px;
        }

        .showFormFields .titleBlock {
            width: 100%;
            display: flex;
            flex-direction: column;
            /* gap: 20px; */
        }

        .showFormFields .titleBlock span {
            display: block;
            text-align: center;
            margin-bottom: 20px;
            margin-top: 35px;
        }

        .showFormFields .textBlock {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .showFormFields .textBlock .btnBlock {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .accountSettingMain .titleBlock .heading,
        .showFormFields .titleBlock .heading {
            color: rgba(89, 150, 65, 1);
        }

        .showFormFields .textBlock ul {
            width: 100%;
            display: flex;
            flex-direction: column;
            list-style: none;
            gap: 25px;
        }

        .showFormFields .textBlock a {
            font-weight: 700;
        }

        .MuiFormControl-root .MuiInputBase-root fieldset {
            border: 1px solid #d8d8d8;
        }

        .showFormFields .textBlock a.btn {
            font-weight: 400;
        }


        .confirmationDataBlock {
            max-width: 100%;
            margin-bottom: 25px;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            /* gap: 10px; */
            border-radius: 12px;
            border: 1px solid rgba(216, 216, 216, 1);
            padding: 16px 20px;
        }

        .confirmationDataBlock .heading {
            background: rgba(217, 217, 217, 1);
            margin-bottom: 10px;
            display: block;
            border-radius: 5px;
            padding: 8px 5px;
            color: rgba(0, 0, 0, 1);
            font-size: 12px;
            line-height: 14px;
        }

        .confirmationDataBlock .singleData {
            display: block;
            color: rgba(0, 0, 0, 1);
            font-size: 12px;
            line-height: 14px;
        }

        .confirmationDataBlock .singleData {
            margin-bottom: 10px;
        }

        .confirmationDataBlock ol {
            font-size: 12px;
            line-height: 14px;
            color: rgba(0, 0, 0, 1);
            display: flex;
            flex-direction: column;
            /* gap: 10px; */
            padding: 0 0 0 18px;
            list-style: lower-alpha;
            margin: 0;
        }

        .confirmationDataBlock ol li {
            margin-bottom: 10px;
        }

        .confirmationDataBlock ol li span {
            clear: both;
            width: 100%;
            display: block;
        }

        .no-page-break {
            page-break-inside: avoid;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #ccc;
        }
       
        .header .logo {
            width: 150px; / Adjust the width as needed /
        }
    </style>
</head>

<body>
    <div class="showFormFields">
        <div class="row">
            {{-- <div class="header">
                <div class="logo">
                    <img src="{{ public_path('/images/e-consul-logo.svg') }}" alt="Logo" style="width: 100%;">
                </div>
            </div> --}}
            <div class="col-lg-12 fullWithCol">
                <div class="logo" style="text-align:right; display:block">
                    <img src="{{ public_path('/images/e-consul-logo.svg') }}" alt="Logo" style="min-width
                    80px ,max-width: 100%;">
                </div>
                <div class="titleBlock">
                    <h6 class="heading h6 mainTopHeading">DV-Lottery 2025</h6>
                </div>
               

                <div class="textBlock">
                    <p>App Id: {{ $detail['id'] }} </p> 
                    <p>Created At: {{ $detail['created_at'] }} </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 fullWithCol">
                <div class="confirmInformationBlock">
                    <div class="titleBlock">
                        <span class="heading mainTopHeading">Part One - Entrant Information</span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">1. Name</span>
                        <ol type="a">
                            <li>
                                <span>Last / Family Name</span>
                                <span>{{ $detail['personal_info']['last_name'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>First Name</span>
                                <span>{{ $detail['personal_info']['first_name'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>Middle Name</span>
                                <span>{{ $detail['personal_info']['middle_name'] ?? 'N/A' }}</span>
                            </li>
                        </ol>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">2. Gender</span>
                        <span class="singleData">{{ $detail['personal_info']['gender'] ?? 'N/A' }}</span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">3. Birth Date</span>
                        <span class="singleData">{{ $detail['personal_info']['birth_date'] ?? 'N/A' }}</span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">4. City Where You Were Born</span>
                        <span class="singleData">{{ $detail['eligibility_status']['city'] ?? 'N/A' }}</span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">5. Country Where You Were Born</span>
                        <span class="singleData">{{ $detail['eligibility_status']['born_country'] ?? 'N/A' }}</span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">6. Country of Eligibility for the DV Program</span>
                        <span class="singleData">{{ $detail['eligibility_status']['live_country'] ?? 'N/A' }}</span>
                    </div>

                    {{-- Uncomment this block if needed --}}
                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">7. Entrant Photograph</span>
                        <span class="singleData">
                            {{ isset($detail['photos']['applicant']) ? '(Photograph received)' : '' }}
                        </span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">8. Mailing Address</span>
                        <ol type="a">
                            <li>
                                <span>Address Line 1</span>
                                <span>{{ $detail['contact_info']['address1'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>Address Line 2 (optional)</span>
                                <span>{{ $detail['contact_info']['address2'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>City/Town</span>
                                <span>{{ $detail['contact_info']['mailing_city'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>District/Country/Province/State</span>
                                <span>{{ $detail['contact_info']['mailing_city'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>Postal Code/Zip Code</span>
                                <span>{{ $detail['contact_info']['mailing_postCode'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>Country</span>
                                <span>{{ $detail['contact_info']['mailing_country'] ?? 'N/A' }}</span>
                            </li>
                        </ol>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">9. Country Where You Live Today</span>
                        <span
                            class="singleData">{{ $detail['eligibility_status']['residence_country'] ?? 'N/A' }}</span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">10. Phone Number</span>
                        <span class="singleData">{{ $detail['contact_info']['phone'] ?? 'N/A' }}</span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">11. E-mail Address</span>
                        <span class="singleData">{{ $detail['contact_info']['email'] ?? 'N/A' }}</span>
                        <span class="singleData">(NOTE: This E-mail address will be used to provide you with
                            additional information if you are selected.)</span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">12. What is the highest level of education you have achieved, as of
                            today?</span>
                        <span class="singleData">{{ $detail['education_level'] ?? 'N/A' }}</span>
                        <span class="singleData">You must have a minimum of a high school diploma reflecting the
                            completion of a full course of study (vocation schools or equivalency degrees are not
                            acceptable) or be a skilled worker in an occupation that requires at least two years of
                            training or experience to qualify (visit <a href="#">http://www.onetonline.org/</a> to
                            see if your occupation qualifies) for a Diversity Visa</span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">13. What is your current marital status?</span>
                        <span class="singleData">{{ $detail['spouse_info']['maritalStatus'] ?? 'N/A' }}</span>
                        <span class="singleData">Legal separation is an arrangement when a couple remains married but
                            live apart, following a court order. If you and your spouse are legally separated, your
                            spouse will not be able to immigrate with you through the Diversity Visa program. You will
                            not be penalized if you choose to enter the name of a spouse from whom you are legally
                            separated.</span>
                        <span class="singleData">If you are not legally separated by a court order, you must include
                            your spouse even if you plan to be divorced before you apply for the Diversity Visa. Failure
                            to list your eligible spouse is grounds for disqualification.</span>
                        <span class="singleData">If your spouse is a U.S. citizen or Lawful Permanent Resident, do
                            not list him/her in your entry.</span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">14. Number of Children</span>
                        <span class="singleData">{{ $detail['children_info'] ?? 'N/A' }}</span>
                        <span class="singleData">Children include all biological children, legally adopted children,
                            and stepchildren who are unmarried and under the age of 21 on the date you submit your
                            entry. You must include all eligible children, even if they do not live with you or if they
                            do not intend to apply for a Diversity Visa as your derivative. Failure to list all eligible
                            children is grounds for disqualification. If your child is a U.S. citizen or Lawful
                            Permanent Resident, do not list him/her in your entry.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 fullWithCol">
                <div class="confirmInformationBlock">
                    <div class="titleBlock">
                        <span class="heading mainTopHeading">Part Two - Derivatives</span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">13. Spouse Name</span>
                        <ol>
                            <li>
                                <span>Last / Family Name</span>
                                <span>{{ $detail['SpouseDetail']['surname'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>First Name</span>
                                <span>{{ $detail['SpouseDetail']['first_name'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>Middle Name</span>
                                <span>{{ $detail['SpouseDetail']['middle_name'] ?? 'N/A' }}</span>
                            </li>
                        </ol>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">13d. Spouse Gender</span>
                        <span class="singleData">{{ $detail['SpouseDetail']['gender'] ?? 'N/A' }}</span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">13e. Spouse Birth Date</span>
                        <span class="singleData">{{ $detail['SpouseDetail']['birth_date'] ?? 'N/A' }}</span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">13f. Spouse Birth City</span>
                        <span class="singleData">{{ $detail['SpouseDetail']['city'] ?? 'N/A' }}</span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">13g. Spouse Birth Country</span>
                        <span class="singleData">{{ $detail['SpouseDetail']['country'] ?? 'N/A' }}</span>
                    </div>

                    <div class="confirmationDataBlock no-page-break">
                        <span class="heading">13h. Spouse Photograph</span>
                        <span
                            class="singleData">{{ isset($detail['photos']['spouse']) ? '(Photograph received)' : 'N/A' }}</span>
                    </div>
                   
                    @if (!empty($detail['ChildDetail']))
                    @foreach ($detail['ChildDetail'] as $index => $child)
                    @php
                    $baseNumber = 14; // Starting number for child details
                    $currentNumber = $baseNumber + $index;
                @endphp
                    <div class="titleBlock">
                        <span class="heading mainTopHeading">Child {{$index+1}}</span>
                    </div>

                   
                            <div class="confirmationDataBlock no-page-break">
                                <span class="heading">{{ $currentNumber}}. Child #{{$index+1}} Name</span>
                                <ol>
                                    <li>
                                        <span>Last / Family Name</span>
                                        <span>{{ $child['surname'] ?? 'N/A' }}</span>
                                    </li>
                                    <li>
                                        <span>First Name</span>
                                        <span>{{ $child['first_name'] ?? 'N/A' }}</span>
                                    </li>
                                    <li>
                                        <span>Middle Name</span>
                                        <span>{{ $child['middle_name'] ?? 'N/A' }}</span>
                                    </li>
                                </ol>
                            </div>

                            <div class="confirmationDataBlock no-page-break">
                                <span class="heading">{{ $currentNumber}}d. Child Gender</span>
                                <span class="singleData">{{ $child['gender'] ?? 'N/A' }}</span>
                            </div>

                            <div class="confirmationDataBlock no-page-break">
                                <span class="heading">{{ $currentNumber}}e. Child Birth Date</span>
                                <span class="singleData">{{ $child['birth_date'] ?? 'N/A' }}</span>
                            </div>

                            <div class="confirmationDataBlock no-page-break">
                                <span class="heading">{{ $currentNumber}}f. Child Birth City</span>
                                <span class="singleData">{{ $child['city'] ?? 'N/A' }}</span>
                            </div>

                            <div class="confirmationDataBlock no-page-break">
                                <span class="heading">{{ $currentNumber}}g. Child Birth Country</span>
                                <span class="singleData">{{ $child['country'] ?? 'N/A' }}</span>
                            </div>

                            <div class="confirmationDataBlock no-page-break">
                                <span class="heading">{{ $currentNumber}}h. Child Photograph</span>
                                <span class="singleData">
                                    {{ isset($detail['children'][$index + 1]['photo']) ? '(Photograph received)' : 'N/A' }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="confirmationDataBlock no-page-break">
                            <span class="heading">No children added.</span>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</body>

</html>
