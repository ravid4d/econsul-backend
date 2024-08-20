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
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    th {
        background-color: #f2f2f2;
    }
</style>

<body>
 
    <h2>User ID: {{ $detail->id }}</h2>

    @if ($detail->eligibility_status)
        <h3>Eligibility Status</h3>
        <table>
            <thead>
                <tr>
                    <th>Residence Country</th>
                    <th>Live Country</th>
                    <th>Born Country</th>
                    <th>City</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $detail->eligibility_status['residence_country'] ?? 'N/A' }}</td>
                    <td>{{ $detail->eligibility_status['live_country'] ?? 'N/A' }}</td>
                    <td>{{ $detail->eligibility_status['born_country'] ?? 'N/A' }}</td>
                    <td>{{ $detail->eligibility_status['city'] ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    @if ($detail->personal_info)
        <h3>Personal Info Data</h3>
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Birth Date</th>
                    <th>Gender</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $detail->personal_info['first_name'] ?? 'N/A' }}</td>
                    <td>{{ $detail->personal_info['middle_name'] ?? 'N/A' }}</td>
                    <td>{{ $detail->personal_info['last_name'] ?? 'N/A' }}</td>
                    <td>{{ $detail->personal_info['birth_date'] ?? 'N/A' }}</td>
                    <td>{{ $detail->personal_info['gender'] ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    @if ($detail->contact_info)
        <h3>Contact Info</h3>
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Mailing Country</th>
                    <th>Mailing City</th>
                    <th>Mailing PostCode</th>
                    <th>Address 1</th>
                    <th>Address 2</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $detail->contact_info['email'] ?? 'N/A' }}</td>
                    <td>{{ $detail->contact_info['phone'] ?? 'N/A' }}</td>
                    <td>{{ $detail->contact_info['mailing_country'] ?? 'N/A' }}</td>
                    <td>{{ $detail->contact_info['mailing_city'] ?? 'N/A' }}</td>
                    <td>{{ $detail->contact_info['mailing_postCode'] ?? 'N/A' }}</td>
                    <td>{{ $detail->contact_info['address1'] ?? 'N/A' }}</td>
                    <td>{{ $detail->contact_info['address2'] ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    @if ($detail->spouse_info)
        <h3>Spouse Info</h3>
        <table>
            <thead>
                <tr>
                    <th>Marital Status</th>
                    <th>Spouse Application</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $detail->spouse_info['maritalStatus'] ?? 'N/A' }}</td>
                    <td>{{ $detail->spouse_info['spouseApplication'] ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    @if ($detail->children_info)
        <h3>Children Info</h3>
        <table>
            <thead>
                <tr>
                    <th>Number of Children</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $detail->children_info ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    @endif
   
</body>

</html>
