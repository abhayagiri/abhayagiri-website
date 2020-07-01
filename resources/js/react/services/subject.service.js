import axios from 'axios';

class SubjectService {

    static async getSubject(id) {
        const
            result = await axios.get(`/api/subjects/${id}`),
            subject = result.data;
        if (subject) {
            return subject;
        } else {
            throw new Error(`Subject ${id} not found`);
        }
    }

    static async getSubjects(subjectGroupId) {
        const
            result = await axios.get(`/api/subject-groups/${subjectGroupId}/subjects`),
            subjects = result.data;
        if (subjects) {
            return subjects;
        } else {
            throw new Error(`Subjects for Subject Gorup ${subjectGroupId} not found`);
        }
    }

    static async getSubjectGroup(id) {
        const
            result = await axios.get(`/api/subject-groups/${id}`),
            subjectGroup = result.data;
        if (subjectGroup) {
            return subjectGroup;
        } else {
            throw new Error(`Subject Group ${id} not found`);
        }
    }

    static async getSubjectGroups() {
        const
            result = await axios.get('/api/subject-groups'),
            subjectGroups = result.data;
        if (subjectGroups) {
            return subjectGroups;
        } else {
            throw new Error(`Subject Groups not found`);
        }
    }
}

export default SubjectService;
