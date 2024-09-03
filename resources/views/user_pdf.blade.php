<!-- resources/views/pdf/user_data.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <title>User Data PDF</title>
</head>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }
</style>

<body>
    <div className="showFormFields">
        <div className="row">
            <div className="col-lg-12 fullWithCol">
                <div className="titleBlock">
                    <h6 className="heading h6 mainTopHeading">Submit Application</h6>
                </div>

                <div className="textBlock">
                    <p>Confirm that information provided is correct. If you need to go back and make a correction, do
                        NOT use the back button on your browser. Instead use the appropriate EDIT button below:</p>
                </div>
            </div>
        </div>

        <div className="row">
            <div className="col-lg-12 fullWithCol">
                <div className="confirmInformationBlock">
                    <div className="titleBlock">
                        <span>Part One - Entrant Information</span>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">1. Name</span>
                        <ol type="a">
                            <li>
                                <span>Last / Family Name</span>
                                <span>{{ $detail->personal_info['last_name'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>First Name</span>
                                <span>{{ $detail->personal_info['first_name'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>Middle Name</span>
                                <span>{{ $detail->personal_info['middle_name'] ?? 'N/A' }}</span>
                            </li>
                        </ol>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">2. Gender</span>
                        <span className="singleData">{{ $detail->personal_info['gender'] ?? 'N/A' }}</span>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">3. Birth Date</span>
                        <span className="singleData">{{ $detail->personal_info['birth_date'] ?? 'N/A' }}</span>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">4. City Where You Were Born</span>
                        <span className="singleData">{{ $detail->eligibility_status['city'] ?? 'N/A' }}</span>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">5. Country Where You Were Born</span>
                        <span className="singleData">{{ $detail->eligibility_status['born_country'] ?? 'N/A' }}</span>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">6. Country of Eligibility for the DV Program</span>
                        <span className="singleData">{{ $detail->eligibility_status['live_country'] ?? 'N/A' }}</span>
                    </div>

                    {{-- <div className="confirmationDataBlock">
                        <span className="heading">7. Entrant Photograph</span>
                        <span className="singleData">
                            {hasPhotograph('applicant') ? "(Photograph received)" : ""}
                        </span>
                    </div> --}}

                    <div className="confirmationDataBlock">
                        <span className="heading">8. Mailing Address</span>
                        <ol type="a">
                            <li>
                                <span>Address Line 1</span>
                                <span>{{ $detail->contact_info['address1'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>Address Line 2 (optional)</span>
                                <span>{{ $detail->contact_info['address2'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>City/Town</span>
                                <span>{{ $detail->contact_info['mailing_city'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>District/Country/Province/State</span>
                                <span>{{ $detail->contact_info['mailing_city'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>Postal Code/Zip Code</span>
                                <span>{{ $detail->contact_info['mailing_postCode'] ?? 'N/A' }}</span>
                            </li>
                            <li>
                                <span>Country</span>
                                <span>{{ $detail->contact_info['mailing_country'] ?? 'N/A' }}</span>
                            </li>
                        </ol>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">9. Country Where You Live Today</span>
                        <span
                            className="singleData">{{ $detail->eligibility_status['residence_country'] ?? 'N/A' }}</span>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">10. Phone Number</span>
                        <span className="singleData">{{ $detail->contact_info['phone'] ?? 'N/A' }}</span>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">11. E-mail Address</span>
                        <span className="singleData">{{ $detail->contact_info['email'] ?? 'N/A' }}</span>
                        <span className="singleData">(NOTE: This E-mail address will be used to provide you with
                            additional information if you are selected.)</span>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">12. What is the highest level of education you have achieved, as of
                            todey?</span>
                        <span className="singleData">{{ $detail->education_level }}</span>
                        <span className="singleData">You must have a minimum of a high school diploma reflecting the
                            completion of a full course of study (vocation schools or equivalency degrees are not
                            acceptable) or be a skilled worker in an occupation that requires at least two years of
                            training or experience to qualify (visit <a href="#">http://www.onetonline.org/</a> to
                            see if your occupation qualifies) for a Diversity Visa</span>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">13. What is your current marital status?</span>
                        <span className="singleData">{{ $detail->spouse_info['maritalStatus'] ?? 'N/A' }}</span>
                        <span className="singleData">Legal separation is an arrangement when a couple remain married but
                            live apart, following a court order. If you and your spouse are legally separated, your
                            spouse will not be able to immigrate with you through the Diversity Visa program. You will
                            not be penalized if you choose to enter the name of a spouse from whom you are legally
                            separated.</span>
                        <span className="singleData">If you are not legally separated by a court order, you must include
                            your spouse even if you plan to be divorced before you apply for the Diversity Visa. Failure
                            to list your eligible spouse is grounds for disqualification.</span>
                        <span className="singleData">If your spouse is a U.S. citizen or Lawful Permanent Resident, do
                            not list him/her in your entry.</span>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">14. Number of Children</span>
                        <span className="singleData">{{ $detail->children_info ?? 'N/A' }}</span>
                        <span className="singleData">Children include all biological children, legally adopted children,
                            and stepchildren who are unmarried and under the age of 21 on the date you submit your
                            entry. You must include all eligible children, even if they do not live with you or if they
                            do not intend to apply for a Diversity Visa as your derivative. Failure to list all eligible
                            children is grounds for disqualification. If your child is a U.S. citizen or Lawful
                            Permanent Resident, do not list him/her in your entry.</span>
                    </div>
                </div>
            </div>
        </div>

        <div className="row">
            <div className="col-lg-12 fullWithCol">
                <div className="confirmInformationBlock">
                    <div className="titleBlock">
                        <span>Part Two - Derivatives</span>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">13. Spouse Name</span>
                        <ol type="a">
                            <li>
                                <span>Last / Family Name</span>
                                <span>{{ $detail->spouse_detail['surname'] }}</span>
                            </li>
                            <li>
                                <span>First Name</span>
                                <span>{{ $detail->spouse_detail['first_name'] }}</span>
                            </li>
                            <li>
                                <span>Middle Name</span>
                                <span>{{ $detail->spouse_detail['birth_date'] }}</span>
                            </li>
                        </ol>
                    </div>
                    <div className="confirmationDataBlock">
                        <span className="heading">13d. Birth Date</span>
                        <span className="singleData">{{ $detail->spouse_detail['birth_date'] }}</span>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">13e. Gender</span>
                        <span className="singleData">{{ $detail->spouse_detail['gender'] }}</span>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">13f. City Where Spouse Was Born</span>
                        <span className="singleData">{{ $detail->spouse_detail['city'] }}</span>
                    </div>

                    <div className="confirmationDataBlock">
                        <span className="heading">13g. Country Where Spouse Was Born</span>
                        <span className="singleData">{{ $detail->spouse_detail['country'] }}</span>
                    </div>

                    {{-- <div className="confirmationDataBlock">
                        <span className="heading">13h. Spouse Photograph</span>
                        <span className="singleData">
                            {hasPhotograph('spouse') ? "(Photograph received)" : ""}
                        </span>
                    </div> --}}

                    @if (!empty($applicantDetails->child_detail))
                        @foreach ($applicantDetails->child_detail as $index => $child)
                            <div class="confirmationDataBlock">
                                <span class="heading">14. Child #{{ $index + 1 }} Name</span>
                                <ol type="a">
                                    <li>
                                        <span>Last / Family Name</span>
                                        <span>{{ $child->surname ?? 'N/A' }}</span>
                                    </li>
                                    <li>
                                        <span>First Name</span>
                                        <span>{{ $child->first_name ?? 'N/A' }}</span>
                                    </li>
                                    <li>
                                        <span>Middle Name</span>
                                        <span>{{ $child->middle_name ?? 'N/A' }}</span>
                                    </li>
                                </ol>
                            </div>

                            <div class="confirmationDataBlock">
                                <span class="heading">14d. Birth Date</span>
                                <span class="singleData">{{ $child->birth_date ?? 'N/A' }}</span>
                            </div>

                            <div class="confirmationDataBlock">
                                <span class="heading">14e. Gender</span>
                                <span class="singleData">{{ $child->gender ?? 'N/A' }}</span>
                            </div>

                            <div class="confirmationDataBlock">
                                <span class="heading">14f. City Where Child Was Born</span>
                                <span class="singleData">{{ $child->city ?? 'N/A' }}</span>
                            </div>

                            <div class="confirmationDataBlock">
                                <span class="heading">14g. Country Where Child Was Born</span>
                                <span class="singleData">{{ $child->country ?? 'N/A' }}</span>
                            </div>

                            <div class="confirmationDataBlock">
                                <span class="heading">14h. Child Photograph</span>
                                <span class="singleData">
                                    {{ hasPhotograph('child' . ($index + 1)) ? 'Photograph received' : '' }}
                                </span>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>

        <div className="row">
            <form onSubmit={handleSubmit}>
                <div className="col-lg-12 fullWithCol">
                    <div className="fromFooterButtonBlock">
                        <a href="#" className="btn withoutBg">EDIT</a>
                        <button className="btn greenBg">CONFIRM</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</body>

</html>
