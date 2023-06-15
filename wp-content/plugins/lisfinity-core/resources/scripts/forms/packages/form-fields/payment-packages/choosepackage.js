import {useEffect, useState} from "@wordpress/element";
import {useDispatch, useSelector} from "react-redux";
import {map, get, omit, isEmpty, find} from 'lodash';
import he from 'he';
import * as actions from "../../../../dashboard/packages/store/actions";
import axios from "axios";


const ChoosePackage  = (props) => {
    const [value, setValue] = useState(props.value);
    const {packages, payment_package} = props;
    const allData = useSelector(state => state);
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

    const checkPackage = (pkg) => {
        const headers = {
            'X-WP-Nonce': lc_data.nonce,
        };
        const url = lc_data.package_and_promotions;
        let data = {
            id: pkg.ID,
            user_id: lc_data.current_user_id,
        };

        console.log("the data for the post request")
        console.log(pkg)
        console.log(data)

        return axios({
            credentials: 'same-origin',
            headers,
            method: 'post',
            url,
            data,
        });
    };

    const getPackage = (pkg) => {
        const response = checkPackage(pkg);
        let temp = {};

        response.then(data => {
            if (data.data) {
                dispatch(actions.setupPackage(data.data));
                if (data.data?.limit_reached) {
                    const costs = {};
                    costs.total = {};
                    costs.total.commission = parseFloat(data.data?.commission.price);
                    dispatch(actions.updateCosts(costs));
                }
            }

            temp.data = data.data;

            console.log("inside getpackage")
            console.log(temp)
        });

        return temp;
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
    const handleInput = (e, pkg, index, fieldId) => {
        const enabled = e.target.checked;
        const input_id = e.target.id
        const name = e.target.name;

        console.log("how bout the package in the handler")
        console.log(pkg)

        if(enabled && !isEmpty(packages)) {
            packages['payment_package'] = [
                pkg.package_id,
                pkg.duration,
                pkg.meta.price,
                input_id
            ];

            const val = {...value, ...packages};
            setValue(val)
            data[props.name] = val;
            let newP = getPackage(pkg)
            data['payment_package'] = newP.data;

            console.log("outside newp getpackage")
            console.log(newP)
            console.log("what is the props and props.name? bue value first, but actually enabled first")
            console.log(enabled)
            console.log(val)
            console.log(value)
            console.log(packages)
            console.log(props)
            console.log(props.name)
            dispatch(actions.updateFormData(data));

            console.log("lets see what the data is after the fact")
            console.log(data)
        }
    }

    return [
        <div style={{
            width: '100%',
        }} className={"packages-wrapper"}>
            {map(packages, (pkg, index) => {
                const val = get(data['packages'], 'payment_package');
                const fieldId = "fieldID_Index" + `-${index}`;
                const chosen_package_id = val && get(value['payment_package'], ['3'] );
                const checked = (fieldId === chosen_package_id) ? true  : false;

                return (!isEmpty(pkg) &&
                        <div key={index} style={{
                            display: 'flex',
                            border: '1px solid rgba(0,0,0,0.1)',
                            marginBottom: '20px',
                            justifyContent: 'space-between',
                        }} className={"package--card"}>
                            <div className={"package--header"} style={{
                                display: 'grid',
                                textAlign: 'center',
                                padding: '20px',
                                background: '#eee',
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
                                <div style={{
                                    marginBottom: '20px',
                                }} className={"package-description"}>
                                    <p>{pkg.post_content}</p>
                                </div>
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
                                   name={"payment_package"}
                                    onChange={e => handleInput(e, pkg, index, fieldId)}
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