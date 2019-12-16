const submitAttestation = function (attestationId, data) {
    return axios.put(`/api/attestations/${attestationId}`, data);
}

export default submitAttestation;