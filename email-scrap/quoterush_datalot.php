<?php
    /**
	@@ Script for posting data for email purposes.
	@@
	@@
	**/	
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);	
	require $_SERVER['DOCUMENT_ROOT'].'/datascrapping/connection.php';
	$db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
	$db_found = mysqli_select_db($db_handle, DB_DATABASE);	
	//echo $sqlproperty_Query="select * from scrapped_datalot_home_insurance_lead where email_status = 0"; 	
	echo $sqlproperty_Query="select * from scrapped_datalot_home_insurance_lead where id = 43"; 		
	
	$property_Query=mysqli_query($db_handle,$sqlproperty_Query);
	if(mysqli_num_rows($property_Query)>0){
		while ($db_field = mysqli_fetch_assoc($property_Query)) {
			echo $id				=	$db_field['id']; 
			echo '<br>';
			$name = explode(' ',$db_field['name']);
			echo $first_name		=	$name[0];
			echo '<br>';
			if(count($name)>1){
				echo $last_name		=	$name[1];
			}
			else {
				echo $last_name		=	' ';
			}
			echo '<br>';
			echo $email				=	$db_field['email'];
				 $dob				=	$db_field['dob'];
			echo '<br>';
			echo $phone				=	$db_field['home_phone'];
			echo '<br>';
			
			$address = explode(',',$db_field['address']);
			
	        $addressfound=str_replace(" ","&",$address[0]);
			$addressfound;
			$addresscity = preg_replace('/([A-Z])/', '/$1', $addressfound);
			$addressfound1=str_replace("&/"," ",$addresscity);
			$addresscityarray=explode('/',$addressfound1);
			
			$addressarray=explode(' ',$addresscityarray[0]);
			print_r($addressarray);
			echo "<br>Number==".$address_number =$addressarray[0];
			echo "<br>street==".$address_street=$addressarray[1]." ".$addressarray[2]." ".$addressarray[3]." ".$addressarray[4];
			$addressline=$address_number." ".$address_street;
			echo "<br>city==".$address_city=$addresscityarray[1];
			
  
  
			if(count($address)==2) {
				$adderss_det = explode(' ',trim($address[1]));
				$street_address	=	$address[0];
			}
			if(count($address)==3) {
				$adderss_det = explode(' ',trim($address[2]));
				$street_address	=	$address[0].', '.$address[1];
			}
			echo '<br>';
			echo "City====".$city				=	$adderss_det[0];
			echo '<br>';
			echo "state====".$state				=	$adderss_det[0];
			echo '<br>';
			
			if(count($adderss_det)>1){
				echo $address_zip_code	=	$adderss_det[1];
			}
			else {
				echo $address_zip_code	=	' ';
			}
			
			echo '<br>';
			echo $year_built		=	$db_field['year_built'];
			echo '<br>';
			echo $apprx_square_footage		=	$db_field['square_footage'];
			echo '<br>';
			
			$occupancy_status	=	$db_field['property_occupancy'];
			if(trim($occupancy_status) == 'Primary' || trim($occupancy_status) == 'Primarya'){
				echo $property_usage = 'Primary Home';
			}
			elseif(trim($occupancy_status) == 'Secondary'){
				echo $property_usage = 'Secondary Home';
			}
			else {
				echo $property_usage = '';
			}
			echo '<br>';
			
			$property_type	=	$db_field['property_type'];
			if(trim($property_type) == 'Single_family' || trim($property_type) == 'Apartment'){
				echo $structure_type = 'Single Family';
			}
			elseif(trim($property_type) == 'Condo' || trim($property_type) == 'Multi_family') {
				echo $structure_type = 'Condominium';
			}
			elseif(trim($property_type) == 'Townhome' ){
				echo $structure_type = 'Townhouse (Center Unit)';
			}
			elseif(trim($property_type) == 'Mobile_Home' ){
				echo $structure_type = 'Mobile Home';
			}
			else {
				echo $structure_type = '';
			}
			//echo '<br>';
			$use='';
			$Occupied_by='';
			$Construction_type ='';
			$square_feet ='';
			$Coverage_amount ='';
			$Coverage_begins_month='';
			$Coverage_begins_day='';
			$Coverage_begins_year ='';
			/*$construction_type 	=	$db_field['construction_type'];
			if($construction_type  == 'Stucco' ){
				echo $construction_type = 'Concrete Block';
			}
			elseif($construction_type  == 'Wood Frame' ){
				echo $construction_type = 'Frame';
			}
			else {
				echo $construction_type = 'Mixed (Block And Frame)';
			}
			echo '<br>';*/

			$deductible 	=	$db_field['desired_policy_deductible'];
			if($deductible  == '2500' ){
				echo $deductible = '$2,500';
			}
			elseif($deductible  == '2000' ){
				echo $deductible = '$2,500';
			}
			elseif($deductible  == '1000' ){
				echo $deductible = '$1,000';
			}
			elseif($deductible  == '500' ){
				echo $deductible = '$500';
			}
			elseif($deductible  == '250' ){
				echo $deductible = '$500';
			}
			elseif($deductible  == '100' ){
				echo $deductible = '$500';
			}
			else {
				echo $deductible = '';
			}
			
			echo '<br>';
			echo $estimated_replacement_cost =	$db_field['replacement_cost'];
			echo '<hr>';
			
			
			
$url1 = "https://quoterush.com/Importer/Json/Import/OQYN455936202/tKLxFXDYra#FLzFz";

$curl = curl_init($url1);
curl_setopt($curl, CURLOPT_URL, $url1);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers1 = array(
   "webpassword: tKLxFXDYra#FLzFz",
   "Content-Type: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers1);

$data = <<<DATA
{
  "Client": {
    "NameFirst": "$first_name",
    "NamePrefix": "",
    "NameMiddle": "",
    "NameLast": "$last_name",
    "NameSuffix": "",
    "EntityType": "Individual",
    "EntityName": "",
    "DateOfBirth": "$dob",
    "Gender": "Male",
    "MaritalStatus": "Married",
    "EducationLevel": "High School Diploma",
    "Industry": "Engineer/Architect/Science/Math",
    "Occupation": "",
    "CreditPermission": "No",
    "AssumedCreditScore": "Very Good",
    "PhoneNumber": "$phone",
    "PhoneNumberAlt": "",
    "PhoneCell": "",
    "EmailAddress": "$email",
    "EPolicy": false,
    "Address": "$addressline",
    "Address2": "",
    "City": "$address_city",
    "State": "$StateProvince",
    "Zip": "$address_zip_code",
    "County": "Broward",
    "Province": "",
    "International": false,
    "Country": "USA",
    "CoApplicantNamePrefix": "",
    "CoApplicantNameFirst": "MISSUS",
    "CoApplicantNameMiddle": "",
    "CoApplicantNameLast": "JSONSAMPLE",
    "CoApplicantNameSuffix": "",
    "CoApplicantDateOfBirth": "09/04/1965",
    "CoApplicantGender": "Female",
    "CoApplicantMaritalStatus": "Married",
    "CoApplicantEducation": "High School Diploma",
    "CoApplicantIndustry": "",
    "CoApplicantOccupation": "Sales",
    "CoApplicantRelationship": "Spouse",
    "Notes": "hi, what is your name?",
    "OverviewNotes": "Here are my notes!    ",
    "Assigned": "sslovak@quoterush.com",
    "LeadSource": "FMAP",
    "LeadStatus": "Quoted",
    "CampaignName": null,
    "Leads360AgentId": null,
    "Leads360LeadId": 0,
    "Leads360CampaignId": null,
    "Leads360CampaignTitle": null,
    "Leads360StatusId": null,
    "Leads360StatusTitle": null,
    "AllWebLeadsId": "",
    "IntegrationKey": "",
    "IntegrationSystem": "",
    "RemoteQuote": null,
    "RemoteQuoteSites": null,
    "SubmittedToTLE": null,
    "Deleted": 0,
    "Zoho_Id": "",
    "Lob_Home": true,
    "Lob_Auto": true,
    "Lob_Flood": false
  },
  "HO": {
    "FormType": "HO-3: Home Owners Policy",
    "Address": "$addressline",
    "Address2": "",
    "City": "$address_city",
    "State": "$StateProvince",
    "Zip": "$address_zip_code",
    "County": "Orange",
    "WithinCityLimits": false,
    "NewPurchase": "No",
    "PurchaseDate": "11/26/2012",
    "PurchasePrice": "345000",
    "UsageType": "Primary",
    "MonthsOwnerOccupied": "9 months or more",
    "MilesToCoast": "39.75",
    "BCEG": "04",
    "Territory": "49",
    "ProtectionClass": "4",
    "FloodZone": "X",
    "FloodPolicy": false,
    "WindOnlyEligible": "No",
    "Notes": "",
    "YearBuilt": "$year_built",
    "StructureType": "Single Family",
    "Families": "1",
    "Stories": "1",
    "Floor": "1",
    "SquareFeet": "2374",
    "UnitsInFirewall": "1",
    "UnitsInBuilding": "12",
    "ConstructionType": "Masonry",
    "Construction": "Concrete Block",
    "FrameConstruction": "",
    "MasonryConstruction": "Concrete Block",
    "FoundationType": "Slab",
    "BasementPercentFinished": "",
    "RoofShape": "Gable",
    "RoofPortionFlat": false,
    "RoofHipPercent": "N/A",
    "RoofMaterial": "Composite Shingle",
    "Pool": "Inground - 300 - 600 sq. ft.",
    "PoolScreenedEnclosure": true,
    "PoolFence": true,
    "PoolDivingboardSlide": false,
    "ScreenedEnclosureSquareFeet": "560",
    "ScreenedCoverage": "$10,000",
    "Jacuzzi": false,
    "HotTub": false,
    "UnderRenovation": false,
    "UnderConstruction": false,
    "UpdateRoofYear": "2012",
    "UpdateRoofType": "Full",
    "UpdatePlumbingYear": "",
    "PlumbingType": "PVC",
    "UpdateElectricalYear": "",
    "ElectricalType": "Circuit Breakers",
    "UpdateHeatingYear": "",
    "PrimaryHeatSource": "Electric",
    "WaterHeaterYear": "2012",
    "CoverageA": "$estimated_replacement_cost",
    "CoverageB": "4500",
    "CoverageBPercent": "2%",
    "CoverageC": "56250",
    "CoverageCPercent": "25%",
    "CoverageD": "22500",
    "CoverageDPercent": "10%",
    "CoverageE": "$300,000",
    "CoverageF": "$5,000",
    "AllOtherPerilsDeductible": "$1,000",
    "HurricaneDeductible": "2%",
    "NamedStormDeductible": "",
    "WindHailDeductible": "",
    "CurrentlyInsured": "Yes",
    "AnyLapses": "No",
    "CurrentCarrier": "Unknown",
    "CurrentAnnualPremium": "3988",
    "CurrentPolicyNumber": "1324567",
    "PolicyEffectiveDate": "04/01/2020",
    "PropertyCurrentPolicyExpDate": "04/01/2020",
    "Mortgage": "No",
    "BillTo": "Insured Pay Plan",
    "Claims": "Yes",
    "ClaimsInfo": "",
    "PriorLiabilityLimits": "",
    "LuxuryItems": "",
    "HaveWindMitForm": true,
    "WindMitFormType": "2012",
    "RoofCovering": "Meets FBC 2001",
    "RoofDeckAttachment": "Level A",
    "RoofWallConnection": "Single Wraps",
    "SecondaryWaterResistance": "No",
    "OpeningProtection": "Hurricane Protection",
    "OpeningProtectionA3": false,
    "Terrain": "Exposure B",
    "WindSpeedDesign": "",
    "BuildingCode": "",
    "WindMitInspectionCompany": "Inspection Express",
    "WindMitInspectorName": "Andy",
    "WindMitInspectorLicenseNumber": "1234",
    "WindMitigationInspectionDate": "02/29/2020",
    "BurglarAlarm": "None",
    "FireAlarm": "None",
    "FireHydrant": "Within 1000 Feet",
    "FireStation": "Within 5 Miles",
    "GatedCommunity": "24 hr Security",
    "Sprinklers": "None",
    "BusinessOnPremises": "",
    "Subdivision": "",
    "ProtectedSubdivision": false,
    "DogLiability": false,
    "EPolicy": false,
    "EquipmentBreakdown": false,
    "FloodEndorsement": false,
    "ASIProgressiveAutoDiscount": false,
    "IdentityTheft": true,
    "IncreaseReplacementCostOnDwelling": true,
    "OpenWaterExposure": false,
    "PersonalInjuryCoverage": true,
    "OptionalPersonalPropertyReplacementCost": true,
    "RefrigeratedContents": false,
    "Smokers": false,
    "ServiceLine": false,
    "SinkholeCoverage": false,
    "TheftVandalism": false,
    "WaterDamageExclusion": false,
    "WoodBurningStove": false,
    "AccreditedBuilder": false,
    "AccreditedBuilderName": "",
    "AdditionalLawOrdinance": "25%",
    "FungusMold": "$10,000",
    "LossAssessment": "",
    "WaterBackup": true,
    "WaterBackupAmount": "$10,000",
    "RoofLossSettlement": "Actual Cash Value",
    "EarthquakeDeductible": "",
    "IBHS": "",
    "BuildingCodeDiscount": "",
    "ImpactResistantRoof": "",
    "WaterDamageFoundation": false,
    "Kitchen1Type": "Builders Grade",
    "Kitchen1Count": "1",
    "FullBathType": "Full Builders Grade",
    "FullBathCount": "2",
    "HalfBathType": "Half Builders Grade",
    "HalfBathCount": "1",
    "GarageList": [
      {
        "Type": "Attached",
        "Capacity": "1",
        "SquareFeet": "280",
        "Deleted": false
      },
      {
        "Type": "Detached",
        "Capacity": "1",
        "SquareFeet": "280",
        "Deleted": false
      }
    ],
    "WallHeight": "9",
    "CentralHeatAndAir": "Yes",
    "Fireplaces": "None",
    "Stoves": "",
    "Carpet": "",
    "Hardwood": "40",
    "Vinyl": "10",
    "Tile": "50",
    "Marble": "",
    "Laminate": "",
    "Terrazzo": "",
    "QualityGrade": "Standard",
    "FoundationShape": "6-7 Corners - L Shape",
    "SiteAccess": "",
    "RCEStyle": "",
    "PorchDeckPatio": "Porch - Open , 49sf*Porch - Open , 14sf*Porch - Open , 168sf",
    "underwriting": {
      "Bankruptcy": false,
      "BankruptcyYears": "",
      "InsuranceCanceled": false,
      "Conviction": false,
      "MoreThan5Acres": true,
      "NotVisible": false,
      "NearIndustrial": false,
      "SinkholeActivity": false,
      "ExistingDamage": false,
      "FireViolations": false,
      "PolybutylenePlumbing": false,
      "CircuitBreakerType": false,
      "ElectricAmps": "",
      "PropertyConverted": false,
      "GarageConverted": false,
      "OilStorage": false,
      "DogWithBiteHistory": false,
      "ViciousDog": false,
      "FarmAnimals": false,
      "FarmAnimalDesc": "",
      "ExoticAnimals": false,
      "ExoticAnimalDesc": "",
      "DogBreeds": "",
      "AbandonedVehicle": false,
      "RoommatesBoarders": false,
      "DomesticEmployee": false,
      "Trampoline": false,
      "SkateboardRamp": false,
      "Rented": false,
      "Unoccupied8Weeks": false,
      "RentalTerm": "",
      "FireExtinguisher": false,
      "Deadbolts": false,
      "OverWater": false,
      "ForSale": false,
      "Foreclosure": false,
      "DaysVacant": "",
      "FoundationNotSecured": false,
      "WaterHeaterNotSecured": false,
      "CrippleWalls": false,
      "CrippleWallsBraced": false,
      "OnCliff": false,
      "OverEarthquake": false
    },
    "CovAFromClient": false,
    "SageSurePolicyId": null
  },
  "PreviousAddress": {
    "Address": "10705 SW 47TH ST",
    "Address2": "",
    "City": "MIAMI",
    "State": "FL",
    "Zip": "33165",
    "County": "Miami-dade",
    "LastMonth": "",
    "LastYear": ""
  },
  "MobileHome": {
    "Manufacturer": null,
    "Make": null,
    "Model": null,
    "Length": null,
    "Width": null,
    "ParkSubdivision": null,
    "Location": null,
    "SerialNumber": null,
    "ANSI": false,
    "TieDownCompliant": false
  },
  "Claims": [
    {
      "Type": "Home",
      "ClaimDetail": "Lightning",
      "Date": "05/15/2017",
      "Amount": "15000",
      "Source": "",
      "ActOfGod": true,
      "CatastrophicLoss": false,
      "PriorResidence": false,
      "Paid": false,
      "Deleted": false
    }
  ],
  "Flood": {
    "FloodZone": "X",
    "CommunityNumber": "",
    "CommunityDescription": "",
    "MapPanel": "",
    "MapPanelSuffix": "",
    "FloodDeductible": "$1,250",
    "PolicyType": "Preferred Risk (PRP)",
    "WaitingPeriod": "Standard 30 Day wait",
    "PriorFloodLoss": "No",
    "Grandfathering": false,
    "HaveFloodElevationCert": false,
    "ElevationCertDate": "",
    "PhotographDate": "",
    "Diagram": "",
    "BuildingCoverage": "225000",
    "ContentsCoverage": "100000",
    "ElevationDifference": "",
    "NonParticipatingFloodCommunity": false,
    "CBRAZone": false,
    "FloodCarrier": "Wright Flood",
    "CarrierType": "NFIP",
    "FloodExpirationDate": "06/15/2018"
  },
  "AutoPolicy": {
    "BodilyInjury": "100/300",
    "CurrentAnnualPremium": null,
    "CurrentCarrier": "USAA",
    "CurrentExpirationDate": "06/15/2018",
    "CurrentlyInsured": "Continuous Insurance - 6+ months",
    "CurrentPolicyTerm": "6 Month",
    "EffectiveDate": "06/15/2018",
    "OwnOrRentHome": null,
    "ResidenceType": "Home (owned)",
    "PriorLiabilityLimits": "100/300",
    "PropertyDamage": "100000",
    "UninsuredMotorist": "100/300",
    "UninsuredMotoristsPropertyDamage": "",
    "YearsAtCurrentResidence": "4",
    "YearsContinuouslyInsured": "20",
    "YearsWithCurrentCarrier": "20",
    "MedicalPayments": "10000",
    "PIPDeductible": "250",
    "PIPCoverge": "",
    "WageLoss": "Excluded",
    "AAAMember": "None",
    "Notes": "",
    "StackedCoverage": false,
    "EFT": false,
    "PIPMedicalDeductible": "",
    "PIPMedicalCoverage": "",
    "CombatTheft": false,
    "SpousalLiability": false,
    "OBEL": false,
    "PIPAddlCoverage": "",
    "GarageState": "FL"
  },
  "Drivers": [
    {
      "NamePrefix": "",
      "NameFirst": "IMPORTAPI",
      "NameMiddle": "",
      "NameLast": "JSONSAMPLE",
      "NameSuffix": "",
      "Gender": "Male",
      "MaritalStatus": "Married",
      "EducationLevel": "High School Diploma",
      "DateOfBirth": "09/21/1962",
      "Occupation": "Engineer/Architect/Science/Math",
      "OccupationTitle": "",
      "OccupationYears": "",
      "Relationship": "Insured",
      "RatedDriver": "Rated",
      "LicenseStatus": "Valid",
      "DateFirstLicensed": "",
      "AgeFirstLicensed": "16",
      "LicenseNumber": "",
      "LicenseState": "FL",
      "SuspendRevoked5": "No",
      "DefensiveDriverCourseDate": "05/01/2017",
      "SR22FR44": "No",
      "Points": "",
      "GoodStudent": false,
      "Training": true,
      "StudentOver100MilesAway": false,
      "Deleted": false,
      "Notes": "",
      "DriverViolationsList": [
        {
          "Violation": "Accident - Not At Fault",
          "ViolationDate": "01/15/2020",
          "ClaimAmount": "5000",
          "CompDetail": "",
          "ClaimAmountBI": "",
          "ClaimAmountPD": "",
          "Deleted": false
        }
      ],
      "MatureDriver": false,
      "GoodDriver": false
    }
  ],
  "Autos": [
    {
      "Year": 2015,
      "Make": "Chevrolet",
      "Model": "Equinox",
      "ModelDetails": "",
      "VIN": "1GNALAEK4FZ111111",
      "AntiTheft": "Passive",
      "PassiveRestraints": "Driver and passenger front airbags, side airbags, and front and rear side curtain airbags w/ occupant sensing deactivation.",
      "AntiLockBrakes": "Yes",
      "OwnershipStatus": "Owned",
      "AnnualMileage": "12500",
      "LengthOfOwnership": "6 months to 1 year",
      "PrimaryDriver": "IMPORTAPI JSONSAMPLE",
      "UseType": "To Work",
      "MilesOneWay": "15",
      "DaysPerWeek": "4",
      "WeeksPerMonth": "4",
      "DaytimeRunningLights": "",
      "ExistingDamage": false,
      "Comprehensive": "200",
      "Collision": "200",
      "UMPDLimit": "",
      "UMPDDed": "",
      "Towing": "75",
      "EAP": false,
      "Rental": "20/600",
      "CostNewValue": "24520",
      "OdometerReading": "",
      "Deleted": false,
      "BodyStyle": "LS 4dr SUV",
      "Drive": "FWD",
      "EngineInfo": "2.4",
      "Fuel": "Gasoline",
      "Transmission": "",
      "DayLights": "",
      "ABS": "Yes",
      "Notes": "",
      "GarageLocation": "Same As The Mailing Address*5441 NW 76TH PL :  : POMPANO BEACH : FL : 33073",
      "GarageIndex": 0,
      "RideShare": false,
      "Telematics": false
    }
  ]
}
DATA;
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
echo "<pre>";
echo "<br>here".$data;
$resp = curl_exec($curl);
curl_close($curl);
var_dump($resp);
				//if(!empty($response_id)){ // checking whether records added or not.
					// update the table.
					echo $sql_update="update scrapped_datalot_home_insurance_lead set email_status=1 where id='".$id."'";
					$result_update = mysqli_query($db_handle, $sql_update);
					
				//}
			
			
		
		}
		
		
	}
	

?>