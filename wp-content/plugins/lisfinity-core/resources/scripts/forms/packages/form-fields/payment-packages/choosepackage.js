import {useEffect, useState} from "@wordpress/element";
import {useDispatch, useSelector} from "react-redux";
import {map, get, omit, isEmpty, find} from 'lodash';
import he from 'he';
import * as actions from "../../../../dashboard/packages/store/actions";
import axios from "axios";


const ChoosePackage  = (props) => {
    const [value, setValue] = useState(props.value);
    const {packages, handlePaymentPackage} = props;
    const allData = useSelector(state => state);
    const fields = allData.fields;
    const data = allData.formData;
    const dispatch = useDispatch();

    const slugify = str =>
        str
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');

    useEffect(() => {
        if (!allData.costs['total']) {
            if (allData.costs === 0) {
                delete allData.costs;
                allData.costs = {};
            }
            allData.costs['total'] = {};
        }
        if (!allData.costs['total']['promo']) {
            allData.costs['total']['promo'] = 0;
        }
    }, []);

    const handleChosenPackage = (pkg, index, fieldId) => (e) => {
        console.log("on to handle package payment")
        console.log("the package first tho")
        console.log(pkg)

        const enabled = e.target.checked;

        if(enabled && !isEmpty(packages)) {
          packages['chosen_package'] = [
            pkg.package_id,
            pkg.duration,
            pkg.meta.price
          ];

          const val = {...value, ...packages};
          setValue(val)
          data[props.name] = val;

          const url = ci_data.ci_single_package;

          let post_data = {
            package_id: pkg.package_id || pkg.ID,
            id: lc_data.current_user_id,
          };

          console.log("post_data")
            console.log(post_data)

          const response = actions.fetchData(url, post_data)

          console.log("Is this response even working")
          console.log(response)

          response.then(result => {
            data['payment_package'] = result.data;
            dispatch(actions.updateFormData(data));
            fields['media']['_product_image_gallery']['limit'] = result.data.image_limit;
            fields['media']['_product-files']['limit'] = result.data.docs_limit;
            fields['media']['_product-videos']['limit'] = result.data.video_limit;
            dispatch(actions.setupFieldGroups(groups))

            console.log(data)
            console.log("are we even in the tehn statement")
          })
        }

        handlePaymentPackage(pkg);

        console.log("after handlePaymentPackage")

    }

    const checkPackage = async (pkg) => {
        const headers = {
            'X-WP-Nonce': lc_data.nonce,
        };
        const url = ci_data.ci_single_package;

        let data = {
            package_id: pkg.package_id || pkg.ID,
            id: lc_data.current_user_id,
        };

        try {
            const response = await axios({
                credentials: 'same-origin',
                headers,
                method: 'post',
                url,
                data,
            })

            console.log("response.data")
            console.log(response.data)

            if(response.data) {
                data['payment_package'] = response.data;


                console.log("response.data")
                console.log(data)
            }

            return response.data
        } catch(error) {
            return alert(error)
        }
    };

    /**
     * Handle storing value when days number for the promotion
     * has been changed.
     * -------------------------------------------------------
     *
     * @param e
     * @param product
     * @param index
     * @param fieldId
     */

    return [
        <div style={{
            width: '100%',
        }} className={"packages-wrapper"}>
            {map(packages, (pkg, index) => {
                const val = get(packages, 'chosen_package');
                const fieldId = "fieldID_Index" + `-${index}`;
                const chosen_package_id = val && get(value['chosen_package'], ['0'] );
                const checked = (pkg.package_id === chosen_package_id) ? true  : false;

                return (!isEmpty(pkg) &&
                        <div key={index} style={{
                            display: 'flex',
                            border: '1px solid rgba(0,0,0,0.1)',
                            marginBottom: '20px',
                            justifyContent: 'space-between',
                        }} className={`package--card ${pkg.class}`}>
                            <div className={`package--header ${pkg.recommended_package ? 'font-bold bg-yellow-100' : 'bg-grey-100'}`} style={{
                                display: 'grid',
                                textAlign: 'center',
                                padding: '20px',
                                borderRight: '1px solid rgba(0,0,0,0.1)',
                                width: '25%',
                            }}>
                                <div style={{
                                    display: 'flex',
                                    alignItems: 'flex-end',
                                    justifyContent: 'center',
                                }} className={"pacakge-title"}>
                                    <h3 style={{
                                        fontSize: '16px'
                                    }}>{pkg.post_title}</h3>
                                </div>
                                <div style={{
                                    fontSize: '28px'
                                }} className={"packge-meta-price"}>
                                    <span>{"$" + pkg.meta.price}</span>
                                </div>
                            </div>
                            <div style={{
                                padding: '20px',
                                width: '55%',
                            }} className={"package--body"}>
                                {pkg.post_content &&
                                    <div style={{
                                        marginBottom: '20px',
                                    }} className={"package-description"}>
                                        <p>{pkg.post_content}</p>
                                    </div>
                                }
                                <div className={"package-meta"}>
                                    <div className={"packge-meta-features"}>
                                        <ul>
                                        {!isEmpty(pkg.features) &&
                                        map(pkg.features, (feature ,i) => {
                                            return (
                                                <li key={feature['uniqueId']} style={{
                                                    listStyle: 'none'
                                                }} dangerouslySetInnerHTML={{ __html: he.decode(feature['package-feature'])}} />
                                            )
                                        })}
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div style={{
                                padding: '40px',
                                textAlign: 'center',
                                display: 'flex',
                                justifyContent: 'center',
                                alignItems: 'center',
                            }} className={"package--choice"}>
                                <label style={{
                                    position: "relative",
                                    zIndex: 9
                                }} htmlFor={fieldId} >
                                <input
                                    style={{
                                        accentColor: '#3ebd93',
                                        width: '35px',
                                        height: '35px',
                                    }}
                                    id={fieldId}
                                   type={"radio"}
                                   name={"chosen_package"}
                                    onChange={handleChosenPackage(pkg, index, fieldId)}
                                    checked={checked}

                                />
                                </label>
                            </div>
                        </div>
                )
            })}
        </div>
    ]
}

export default ChoosePackage;